<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\PaymentSetting;
use App\Models\VehicleRate;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class BookingController extends Controller
{
    /**
     * Create a new booking.
     *
     * Validation lives here; all business logic is delegated to CreateBookingAction.
     * The email + PDF are dispatched to the queue so the response returns immediately.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id'                               => 'required|integer|exists:schedules,id',
            'origin'                                    => 'required|string',
            'destination'                               => 'required|string',
            'departure_date'                            => 'required|date',
            'trip_type'                                 => 'required|string|in:one_way,round_trip',
            'return_date'                               => 'nullable|date',
            'client_name'                               => 'required|string|max:255',
            'client_email'                              => 'required|email',
            'selected_transport_class_id'               => 'nullable|integer|exists:transport_classes,id',
            'selected_schedule_accommodation_id'        => 'nullable|integer|exists:schedule_accommodations,id',
            'has_vehicle'                               => 'nullable|boolean',
            'vehicle_type'                              => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_plate_number'                      => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_price'                             => 'required_if:has_vehicle,true|nullable|numeric|min:0',
            'passengers'                                => 'required|array|min:1',
            'passengers.*.name'                         => 'required|string|max:255',
            'passengers.*.type'                         => 'required|string|in:adult,child',
            'passengers.*.discount_id'                  => 'nullable|integer|exists:discounts,id',
            'passengers.*.school_name'                  => 'nullable|string|max:255',
            'passengers.*.id_number'                    => 'nullable|string|max:255',
            'accommodation_ids'                         => 'nullable|array',
            'accommodation_ids.*'                       => 'integer|exists:accommodations,id',
            'voucher_code'                              => 'nullable|string|max:50',
            'return_schedule_id'                        => 'nullable|integer|exists:schedules,id',
            'selected_return_schedule_accommodation_id' => 'nullable|integer|exists:schedule_accommodations,id',
            'use_points'                                => 'nullable|boolean',
        ]);

        try {
            /** @var \App\Models\Booking $booking */
            $booking = app(\App\Actions\Bookings\CreateBookingAction::class)
                ->execute($request->all(), auth()->guard('api')->user());

            // Dispatch the PDF generation + email to the queue (non-blocking)
            \App\Jobs\SendBookingConfirmationJob::dispatch($booking);

            return response()->json([
                'status'                  => 'success',
                'message'                 => 'Booking created successfully!',
                'booking_id'              => $booking->id,
                'transaction_number'      => $booking->transaction_number,
                'subtotal_before_voucher' => floatval($booking->subtotal_before_voucher),
                'voucher_code'            => $booking->voucher_code,
                'voucher_discount_amount' => floatval($booking->voucher_discount_amount),
                'points_used'             => $booking->points_used,
                'points_discount'         => floatval($booking->points_discount),
                'total_price'             => floatval($booking->total_price),
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create booking: ' . $e->getMessage()], 500);
        }
    }

    public function vehicleRates()
    {
        $rates = Cache::remember('api:vehicle_rates', now()->addHours(6), function () {
            return VehicleRate::query()->where('is_active', true)->orderBy('sort_order')->get();
        });

        return response()->json([
            'status'        => 'success',
            'vehicle_rates' => $rates,
        ]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'lookup_token' => 'nullable|string',
        ]);

        $isAuthenticated = auth('api')->check() && auth('api')->user()->email === $request->input('email');

        if (!$isAuthenticated) {
            if (!$request->input('lookup_token')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lookup token or authentication required.',
                ], 401);
            }

            $verifiedEmail = Cache::get('booking_lookup_token:' . hash('sha256', $request->input('lookup_token')));
            if (! $verifiedEmail || strtolower($verifiedEmail) !== strtolower($request->input('email'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email verification is required before viewing bookings.',
                ], 401);
            }
        }

        $bookings = \App\Models\Booking::where('client_email', '=', $request->input('email'), 'and')
            ->with(['passengers.discount', 'accommodations', 'transaction', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = $bookings->map(function (Booking $booking) {
            $data = $booking->toArray();
            $transaction = $booking->transaction;
            if ($transaction?->confirmation_pdf) {
                $data['confirmation_pdf_url'] = asset('storage/' . $transaction->confirmation_pdf);
            }
            $data['confirmation_url'] = $transaction?->confirmation_url;
            $data['ticket_url'] = URL::temporarySignedRoute(
                'ticket.download',
                now()->addDays(7),
                ['booking' => $booking->id]
            );

            return $data;
        });

        return response()->json([
            'status' => 'success',
            'bookings' => $bookings
        ]);
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'proof' => 'required|file|image|max:10240', // max 10MB file
        ]);

        $booking = Booking::whereKey($id)
            ->where('client_email', $request->input('email'))
            ->with('transaction')
            ->firstOrFail();
        $transaction = $booking->transaction;

        if (!$transaction) {
            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_status' => 'unpaid',
            ]);
        }

        $path = $request->file('proof')->store('proofs', 'public');

        $transaction->update([
            'proof_of_payment' => $path,
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Proof of payment uploaded successfully!',
            'proof_url' => asset('storage/' . $path),
        ]);
    }

    public function paymentSettings()
    {
        $data = Cache::remember('api:payment_settings', now()->addHours(6), function () {
            $settings = PaymentSetting::current();

            $qrCodeUrl = null;
            if ($settings->qr_code_path) {
                $qrCodeUrl = asset('storage/' . $settings->qr_code_path);
            }

            return [
                'qr_code_url'            => $qrCodeUrl,
                'fee_per_person'         => floatval($settings->fee_per_person),
                'fee_per_accommodation'  => floatval($settings->fee_per_accommodation),
            ];
        });

        return response()->json(array_merge(['status' => 'success'], $data));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'action' => 'nullable|string|in:start,confirm',
            'refund_destination' => 'nullable|string|max:255',
        ]);

        $booking = Booking::whereKey($id)
            ->where('client_email', $request->input('email'))
            ->with('transaction')
            ->firstOrFail();

        if (! $booking->canCancelOrRebook() || $booking->status !== 'pending' || ! $booking->transaction || ! in_array($booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This booking can no longer be cancelled.'
            ], 400);
        }

        if ($request->input('action', 'confirm') === 'start') {
            $expiresAt = now()->addMinutes(5);
            $booking->update(['cancellation_window_expires_at' => $expiresAt]);

            return response()->json([
                'status' => 'success',
                'message' => 'Cancellation window started.',
                'expires_at' => $expiresAt->toISOString(),
                'cancellation_fee' => (float) $booking->getCancellationFeeAmount(),
                'refund_amount' => (float) $booking->getRefundAmount(),
            ]);
        }

        if (! $booking->cancellation_window_expires_at || now()->greaterThan($booking->cancellation_window_expires_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The five-minute cancellation window has expired. Start a new cancellation request.',
            ], 400);
        }

        $request->validate(['refund_destination' => 'required|string|max:255']);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_fee' => $booking->getCancellationFeeAmount(),
            'refund_amount' => $booking->getRefundAmount(),
            'refund_destination' => $request->input('refund_destination'),
            'cancellation_window_expires_at' => null,
        ]);

        app(\App\Services\GraciaPointsService::class)->reversePointsForBooking($booking);
        app(\App\Services\GraciaPointsService::class)->refundRedeemedPoints($booking);

        if ($booking->transaction) {
            $booking->transaction->update(['payment_status' => 'cancelled']);
        }

        // Send a user-specific FCM push notification to the cancelling user's phone
        try {
            $userTopic = 'user_' . md5(strtolower(trim($booking->client_email)));
            $messaging = app('firebase.messaging');
            $notification = \Kreait\Firebase\Messaging\Notification::create(
                '✈️ Booking Cancelled',
                "Booking #{$booking->transaction_number} has been cancelled. Refund: ₱{$booking->refund_amount}. Please allow 3–5 business days for processing."
            );
            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('topic', $userTopic)
                ->withNotification($notification);
            $messaging->send($message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FCM cancellation push failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Booking cancelled successfully.',
            'cancellation_fee' => (float) $booking->cancellation_fee,
            'refund_amount' => (float) $booking->refund_amount,
        ]);
    }

    public function rebook(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'proof' => 'required|file|image|max:10240',
        ]);

        $booking = Booking::whereKey($id)
            ->where('client_email', $request->input('email'))
            ->with('transaction')
            ->firstOrFail();

        if (! $booking->canCancelOrRebook() || ! in_array($booking->status, ['pending', 'unpaid'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This booking can no longer be rebooked.',
            ], 400);
        }

        if ($booking->rebooking_status === 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'A rebooking request is already pending verification.',
            ], 400);
        }

        $transaction = $booking->transaction ?: Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'unpaid',
        ]);
        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')->store('rebooking_proofs', 'public')
            : null;
        $rebookingFee = $booking->getRebookingFeeAmount();

        $transaction->update([
            'rebooking_fee' => $rebookingFee,
            'rebooking_proof_of_payment' => $proofPath,
            'payment_status' => 'pending',
        ]);
        $booking->update([
            'rebooking_status' => 'pending',
            'rebooking_departure_date' => $request->input('departure_date'),
            'rebooking_return_date' => $request->input('return_date'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rebooking request submitted for verification.',
            'rebooking_fee' => (float) $rebookingFee,
            'rebooking_status' => 'pending',
        ]);
    }
}
