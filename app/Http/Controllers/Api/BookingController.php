<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\PaymentSetting;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
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
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.type' => 'required|string|in:adult,child',
            'passengers.*.discount_id' => 'nullable|integer|exists:discounts,id',
            'accommodation_ids' => 'nullable|array',
            'accommodation_ids.*' => 'integer|exists:accommodations,id',
        ]);

        $schedule = Schedule::findOrFail($request->input('schedule_id'));

        $transaction = null;

        try {
            DB::beginTransaction();

            $booking = Booking::create([
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
                'client_name' => $request->input('client_name'),
                'client_email' => $request->input('client_email'),
                'total_price' => $this->calculatePrice($schedule, $request->input('passengers'), $request->input('trip_type'), $request->input('accommodation_ids', [])),
                'status' => 'pending',
            ]);

            foreach ($request->input('passengers') as $passengerData) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => $passengerData['type'],
                    'name' => $passengerData['name'],
                    'discount_id' => $passengerData['discount_id'] ?? null,
                ]);
            }

            $accommodationIds = $request->input('accommodation_ids', []);
            if (!empty($accommodationIds)) {
                $catalog = \App\Models\Accommodation::whereIn('id', $accommodationIds)->get();
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
                $booking->load('passengers.discount', 'accommodations', 'transaction', 'schedule');
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

                Mail::to($booking->client_email)->send(new BookingConfirmation($booking, $ticketUrl, $receiptPath));
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

    private function calculatePrice(Schedule $schedule, array $passengers, string $tripType, array $accommodationIds = []): float
    {
        $schedulePrice = floatval($schedule->price);
        $tripMultiplier = $tripType === 'round_trip' ? 2 : 1;
        $discounts = Discount::all()->keyBy('id');

        $ferryTotal = collect($passengers)->sum(function (array $passenger) use ($schedulePrice, $tripMultiplier, $discounts) {
            $fare = $schedulePrice * $tripMultiplier;

            if (! empty($passenger['discount_id'])) {
                $discount = $discounts->get($passenger['discount_id']);
                if ($discount) {
                    $fare -= $fare * (floatval($discount->percentage) / 100);
                }
            }

            return $fare;
        });

        $accommodationsTotal = 0;
        if (!empty($accommodationIds)) {
            $accommodationsTotal = \App\Models\Accommodation::whereIn('id', $accommodationIds)->sum('price');
        }

        $settings = PaymentSetting::current();
        $payingTravelers = count($passengers);
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person ?? 0))
            + (count($accommodationIds) * floatval($settings->fee_per_accommodation ?? 0));

        return $ferryTotal + floatval($accommodationsTotal) + $serviceFee;
    }
}
