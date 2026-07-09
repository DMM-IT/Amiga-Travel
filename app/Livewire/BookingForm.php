<?php

namespace App\Livewire;

use App\Mail\BookingConfirmation;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\FerryRoute;
use App\Models\Passenger;
use App\Models\PaymentSetting;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;

class BookingForm extends Component
{
    public int $step = 1;
    public string $origin = '';
    public string $destination = '';
    public ?string $departure_date = null;
    public ?string $return_date = null;
    public int $adults = 1;
    public int $children = 0;
    public int $infants = 0;
    public ?int $selected_schedule_id = null;

    // Each entry: ['type' => 'adult'|'child'|'infant', 'name' => '', 'discount_id' => null]
    public array $passengers = [];

    // Selected catalog accommodation ids, e.g. [3 => true, 5 => true]
    public array $selected_accommodation_ids = [];

    public string $client_name = '';
    public string $client_email = '';
    public string $recaptchaToken = '';
    public \Illuminate\Support\Collection $discounts;
    public \Illuminate\Support\Collection $accommodationCatalog;
    public array $availableSchedules = [];
    public array $origins = [];

    public function mount(): void
    {
        $this->discounts = Discount::orderBy('name')->get();
        $this->accommodationCatalog = Accommodation::where('is_active', true)->orderBy('name')->get();
        $this->origins = FerryRoute::activeOrigins();
        $this->availableSchedules = [];
        $this->syncPassengerEntries();
    }

    #[Computed]
    public function destinations(): array
    {
        if (blank($this->origin)) {
            return [];
        }

        return FerryRoute::activeDestinationsFor($this->origin);
    }

    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'passengers.') || str_starts_with($propertyName, 'selected_accommodation_ids')) {
            return;
        }

        if (in_array($propertyName, ['origin', 'destination', 'departure_date'], true)) {
            $this->selected_schedule_id = null;
            $this->availableSchedules = [];
        }

        if ($propertyName === 'origin') {
            $this->destination = '';
        }

        $this->validateOnly($propertyName, $this->allRules());
    }

    public function nextStep(): void
    {
        $rules = $this->stepRules();

        if (! empty($rules)) {
            $this->validate($rules);
        }

        if ($this->step === 1) {
            $this->availableSchedules = $this->getAvailableSchedules();

            if (empty($this->availableSchedules)) {
                throw ValidationException::withMessages([
                    'departure_date' => 'No ferry schedules are available for this route on the selected date. Try another date or contact Amiga Gracia Travel Services.',
                ]);
            }
        }

        if ($this->step === 2) {
            $this->assertSelectedScheduleIsValid();
        }

        if ($this->step === 3) {
            $this->syncPassengerEntries();
        }

        if ($this->step < 6) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function selectSchedule(int $scheduleId): void
    {
        $this->selected_schedule_id = $scheduleId;
    }

    protected function getAvailableSchedules(): array
    {
        return Schedule::query()
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date)
            ->get()
            ->map(fn (Schedule $schedule) => $schedule->toBookingArray())
            ->values()
            ->all();
    }

    /**
     * Build (or resize) the per-passenger entries based on the adult/child/infant
     * counts entered in step 3, preserving names/discounts already typed in for
     * passengers that still exist after a count change.
     */
    protected function syncPassengerEntries(): void
    {
        $existingByType = collect($this->passengers)->groupBy('type');

        $rebuilt = [];

        foreach (['adult' => $this->adults, 'child' => $this->children, 'infant' => $this->infants] as $type => $count) {
            $existing = $existingByType->get($type, collect())->values();

            for ($i = 0; $i < $count; $i++) {
                $rebuilt[] = $existing->get($i, [
                    'type' => $type,
                    'name' => '',
                    'discount_id' => null,
                ]);
            }
        }

        $this->passengers = $rebuilt;
    }

    public function toggleAccommodation(int $accommodationId): void
    {
        if (isset($this->selected_accommodation_ids[$accommodationId])) {
            unset($this->selected_accommodation_ids[$accommodationId]);
        } else {
            $this->selected_accommodation_ids[$accommodationId] = true;
        }
    }

    public function submit()
    {
        $this->validate($this->allRules());
        $this->assertSelectedScheduleIsValid();

        if (! app()->environment('local')) {
            Validator::make([
                'recaptchaToken' => $this->recaptchaToken,
            ], [
                'recaptchaToken' => 'required|captcha',
            ])->validate();
        }

        $schedule = Schedule::query()
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date)
            ->findOrFail($this->selected_schedule_id);

        $transaction = null;

        DB::transaction(function () use (&$transaction, $schedule) {
            $booking = Booking::create([
                'transaction_number' => $this->generateTransactionNumber(),
                'origin' => $this->origin,
                'destination' => $this->destination,
                'departure_date' => $this->departure_date,
                'return_date' => $this->return_date,
                'schedule_id' => $schedule->id,
                'schedule_service' => $schedule->service_name,
                'schedule_departure_time' => $schedule->formatted_departure,
                'schedule_arrival_time' => $schedule->formatted_arrival,
                'schedule_price' => $schedule->price,
                'client_name' => $this->client_name,
                'client_email' => $this->client_email,
                'total_price' => $this->calculateTotalPrice(),
                'status' => 'pending',
            ]);

            foreach ($this->passengers as $passenger) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => $passenger['type'],
                    'name' => $passenger['name'] ?: null,
                    'discount_id' => $passenger['discount_id'] ?: null,
                ]);
            }

            $selectedIds = array_keys(array_filter($this->selected_accommodation_ids));
            $catalog = Accommodation::whereIn('id', $selectedIds)->get();

            foreach ($catalog as $accommodation) {
                $booking->accommodations()->attach($accommodation->id, [
                    'price' => $accommodation->price,
                ]);
            }

            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_status' => 'unpaid',
            ]);

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
        });

        return redirect()->route('payment.show', $transaction);
    }

    public function render()
    {
        return view('livewire.booking-form');
    }

    protected function stepRules(): array
    {
        return match ($this->step) {
            1 => [
                'origin' => 'required|string|max:255',
                'destination' => 'required|string|max:255',
                'departure_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:departure_date',
            ],
            2 => [
                'selected_schedule_id' => 'required|integer|exists:schedules,id',
            ],
            3 => [
                'adults' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'infants' => 'required|integer|min:0',
            ],
            4 => [
                'passengers.*.discount_id' => 'nullable|exists:discounts,id',
            ],
            5 => [
                //
            ],
            6 => [
                'client_name' => 'required|string|max:255',
                'client_email' => 'required|email',
                'recaptchaToken' => 'required|string',
            ],
            default => [],
        };
    }

    protected function allRules(): array
    {
        return [
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'selected_schedule_id' => 'required|integer|exists:schedules,id',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'infants' => 'required|integer|min:0',
            'passengers.*.discount_id' => 'nullable|exists:discounts,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'recaptchaToken' => $this->recaptchaRule(),
        ];
    }

    protected function assertSelectedScheduleIsValid(): void
    {
        if (! $this->selected_schedule_id) {
            throw ValidationException::withMessages([
                'selected_schedule_id' => 'Please select a ferry schedule.',
            ]);
        }

        $isValid = Schedule::query()
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date)
            ->where('id', $this->selected_schedule_id)
            ->exists();

        if (! $isValid) {
            throw ValidationException::withMessages([
                'selected_schedule_id' => 'The selected schedule is no longer available for this route and date.',
            ]);
        }
    }

    protected function generateTransactionNumber(): string
    {
        return 'AGT-' . now()->format('Ymd') . '-' . rand(1000, 9999);
    }

    public function calculateTotalPrice(): float
    {
        $schedulePrice = $this->getSelectedSchedulePrice();
        $tripMultiplier = $this->return_date ? 2 : 1;
        $discountsById = $this->discounts->keyBy('id');

        $ferryTotal = collect($this->passengers)->sum(function (array $passenger) use ($schedulePrice, $tripMultiplier, $discountsById) {
            $fare = $schedulePrice * $tripMultiplier;

            if (! empty($passenger['discount_id'])) {
                $discount = $discountsById->get($passenger['discount_id']);

                if ($discount) {
                    $fare -= $fare * (floatval($discount->percentage) / 100);
                }
            }

            return $fare;
        });

        $selectedIds = array_keys(array_filter($this->selected_accommodation_ids));

        $accommodationsTotal = $this->accommodationCatalog
            ->whereIn('id', $selectedIds)
            ->sum(fn ($item) => floatval($item->price));

        $settings = PaymentSetting::current();

        // Service fee: charged per traveler (infants excluded) and per selected accommodation.
        $payingTravelers = collect($this->passengers)->where('type', '!=', 'infant')->count();
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person))
            + (count($selectedIds) * floatval($settings->fee_per_accommodation));

        return $ferryTotal + $accommodationsTotal + $serviceFee;
    }

    protected function getSelectedSchedulePrice(): float
    {
        if (! $this->selected_schedule_id) {
            return 0;
        }

        $schedule = collect($this->availableSchedules)
            ->firstWhere('id', $this->selected_schedule_id);

        if ($schedule) {
            return floatval($schedule['price']);
        }

        return floatval(Schedule::query()->whereKey($this->selected_schedule_id)->value('price') ?? 0);
    }

    protected function recaptchaRule(): string
    {
        return app()->environment('local') ? 'nullable|string' : 'required|string';
    }
}
