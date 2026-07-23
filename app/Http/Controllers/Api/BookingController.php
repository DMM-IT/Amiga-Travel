<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\ScheduleAccommodation;
use App\Models\TransportClass;
use App\Models\VehicleRate;
use App\Models\Accommodation;
use App\Models\Transaction;
use App\Models\PaymentSetting;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use App\Mail\BookingCreated;
use Spatie\LaravelPdf\Facades\Pdf;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|integer|exists:schedules,id',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'trip_type' => 'required|string|in:one_way,round_trip',
            'return_date' => 'nullable|date',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'selected_transport_class_id' => 'nullable|integer|exists:transport_classes,id',
            'selected_schedule_accommodation_id' => 'nullable|integer|exists:schedule_accommodations,id',
            'has_vehicle' => 'nullable|boolean',
            'vehicle_type' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_plate_number' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_price' => 'required_if:has_vehicle,true|nullable|numeric|min:0',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.type' => 'required|string|in:adult,child',
            'passengers.*.discount_id' => 'nullable|integer|exists:discounts,id',
            'passengers.*.school_name' => 'nullable|string|max:255',
            'passengers.*.id_number' => 'nullable|string|max:255',
            'passengers.*.seat_number' => 'nullable|string|max:255',
            'passengers.*.seat_row' => 'nullable|integer',
            'passengers.*.seat_section' => 'nullable|string|max:255',
            'accommodation_ids' => 'nullable|array',
            'accommodation_ids.*' => 'integer|exists:accommodations,id',
        ]);

        $schedule = Schedule::findOrFail($request->input('schedule_id'));
        $scheduleAccommodation = $request->input('selected_schedule_accommodation_id')
            ? \App\Models\ScheduleAccommodation::find($request->input('selected_schedule_accommodation_id'))
            : null;

        $transaction = null;

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->guard('api')->user()?->id,
                'transaction_number' => 'AGT-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'origin' => $request->input('origin'),
                'destination' => $request->input('destination'),
                'departure_date' => $request->input('departure_date'),
                'return_date' => $request->input('return_date'),
                'schedule_id' => $schedule->id,
                'schedule_service' => $schedule->service_name,
                'schedule_departure_time' => $schedule->formatted_departure,
                'schedule_arrival_time' => $schedule->formatted_arrival,
                'schedule_price' => $schedule->price,
                'schedule_accommodation_id' => $scheduleAccommodation?->id,
                'schedule_accommodation_name' => $scheduleAccommodation?->name,
                'schedule_accommodation_price' => $scheduleAccommodation?->price,
                'client_name' => $request->input('client_name'),
                'client_email' => $request->input('client_email'),
                'total_price' => $this->calculatePrice(
                    $schedule,
                    $request->input('passengers'),
                    $request->input('trip_type'),
                    $request->input('accommodation_ids', []),
                    $scheduleAccommodation,
                    $request->input('selected_transport_class_id'),
                    $request->input('has_vehicle', false),
                    $request->input('vehicle_price', 0)
                ),
                'status' => 'pending',
                'has_vehicle' => $request->input('has_vehicle', false),
                'vehicle_type' => $request->input('vehicle_type'),
                'vehicle_plate_number' => $request->input('vehicle_plate_number'),
                'vehicle_price' => $request->input('vehicle_price'),
            ]);

            foreach ($request->input('passengers') as $passengerData) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => $passengerData['type'],
                    'name' => $passengerData['name'],
                    'discount_id' => $passengerData['discount_id'] ?? null,
                    'school_name' => $passengerData['school_name'] ?? null,
                    'id_number' => $passengerData['id_number'] ?? null,
                    'seat_number' => $passengerData['seat_number'] ?? null,
                    'seat_row' => $passengerData['seat_row'] ?? null,
                    'seat_section' => $passengerData['seat_section'] ?? null,
                ]);
            }

            if ($request->input('selected_transport_class_id')) {
                $transportClass = \App\Models\TransportClass::find($request->input('selected_transport_class_id'));
                if ($transportClass) {
                    $booking->transportClasses()->attach($transportClass->id, [
                        'price' => $transportClass->effective_price,
                    ]);
                }
            }

            $accommodationIds = $request->input('accommodation_ids', []);
            if (!empty($accommodationIds)) {
                $catalog = \App\Models\Accommodation::whereIn('id', $accommodationIds, 'and', false)->get();
                foreach ($catalog as $accommodation) {
                    $booking->accommodations()->attach($accommodation->id, [
                        'price' => $accommodation->price,
                    ]);
                }
            }

            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_status' => 'unpaid',
            ]);

            DB::commit();

            // Try sending email receipt (optional)
            try {
                $booking->load('passengers.discount', 'accommodations', 'transaction', 'schedule', 'transportClasses', 'scheduleAccommodation');
                $receiptPath = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');
                if (! file_exists(dirname($receiptPath))) {
                    mkdir(dirname($receiptPath), 0755, true);
                }

                Pdf::driver('dompdf')
                    ->view('pdf.receipt', ['booking' => $booking])
                    ->save($receiptPath);

                $ticketUrl = URL::temporarySignedRoute(
                    'ticket.download',
                    now()->addDays(7),
                    ['booking' => $booking->id]
                );

                Mail::to($booking->client_email)->send(new BookingCreated($booking, $receiptPath));
            } catch (\Exception $e) {
                // Ignore mailing/PDF errors so it doesn't crash the api response
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully!',
                'booking_id' => $booking->id,
                'transaction_number' => $booking->transaction_number,
                'total_price' => floatval($booking->total_price),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculatePrice(
        Schedule $schedule,
        array $passengers,
        string $tripType,
        array $accommodationIds = [],
        $scheduleAccommodation = null,
        $selectedTransportClassId = null,
        $hasVehicle = false,
        $vehiclePrice = 0
    ): float {
        $schedulePrice = floatval($schedule->price);
        $scheduleAccommodationPrice = $scheduleAccommodation ? floatval($scheduleAccommodation->price) : 0;
        $tripMultiplier = $tripType === 'round_trip' ? 2 : 1;
        $discounts = Discount::all()->keyBy('id');

        $ferryTotal = collect($passengers)->sum(function (array $passenger) use ($schedulePrice, $scheduleAccommodationPrice, $tripMultiplier, $discounts) {
            $fare = ($schedulePrice + $scheduleAccommodationPrice) * $tripMultiplier;

            if (! empty($passenger['discount_id'])) {
                $discount = $discounts->get($passenger['discount_id']);
                if ($discount) {
                    $fare -= $fare * (floatval($discount->percentage) / 100);
                }
            }

            return $fare;
        });

        $transportClassTotal = 0;
        if ($selectedTransportClassId) {
            $transportClass = \App\Models\TransportClass::find($selectedTransportClassId);
            if ($transportClass) {
                $transportClassTotal = floatval($transportClass->effective_price);
            }
        }

        $accommodationsTotal = 0;
        if (!empty($accommodationIds)) {
            $accommodationsTotal = \App\Models\Accommodation::whereIn('id', $accommodationIds, 'and', false)->sum('price');
        }

        $vehicleTotal = $hasVehicle ? floatval($vehiclePrice ?? 0) : 0;

        $settings = PaymentSetting::current();
        $payingTravelers = count($passengers);
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person ?? 0));

        return $ferryTotal + $transportClassTotal + $accommodationsTotal + $vehicleTotal + $serviceFee;
    }

    public function vehicleRates()
    {
        $rates = \App\Models\VehicleRate::query()->where('is_active', true)->orderBy('sort_order')->get();
        return response()->json([
            'status' => 'success',
            'vehicle_rates' => $rates
        ]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'lookup_token' => 'required|string',
        ]);

        $verifiedEmail = Cache::get('booking_lookup_token:' . hash('sha256', $request->input('lookup_token')));
        if (! $verifiedEmail || strtolower($verifiedEmail) !== strtolower($request->input('email'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email verification is required before viewing bookings.',
            ], 401);
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
        $settings = PaymentSetting::current();

        $qrCodeUrl = null;
        if ($settings->qr_code_path) {
            $qrCodeUrl = asset('storage/' . $settings->qr_code_path);
        }

        return response()->json([
            'status'    => 'success',
            'qr_code_url' => $qrCodeUrl,
            'fee_per_person' => floatval($settings->fee_per_person),
            'fee_per_accommodation' => floatval($settings->fee_per_accommodation),
        ]);
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

        if ($booking->transaction) {
            $booking->transaction->update(['payment_status' => 'cancelled']);
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
