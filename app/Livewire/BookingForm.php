<?php

namespace App\Livewire;

use App\Mail\BookingConfirmation;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\Passenger;
use App\Models\PaymentSetting;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPdf\Facades\Pdf;
use Livewire\Component;

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
    public array $origins = ['Manila', 'Batangas', 'Lucena', 'Cebu'];
    public array $destinations = ['Boracay', 'Bohol', 'Palawan', 'Cebu'];

    public function mount(): void
    {
        $this->discounts = Discount::orderBy('name')->get();
        $this->accommodationCatalog = Accommodation::where('is_active', true)->orderBy('name')->get();
        $this->availableSchedules = [];
        $this->syncPassengerEntries();
    }

    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'passengers.') || str_starts_with($propertyName, 'selected_accommodation_ids')) {
            return;
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
        $prices = [1800, 2200, 2700];

        return collect([1, 2, 3])->map(fn ($id) => [
            'id' => $id,
            'departure' => sprintf('%02d:00', 6 + $id * 2),
            'arrival' => sprintf('%02d:%02d', 9 + $id * 2, 30),
            'duration' => '3h 30m',
            'price' => $prices[$id - 1],
            'service' => $id === 1 ? 'Fast Ferry' : 'Express Ferry',
            'availability' => $id === 2 ? 'Limited seats' : 'Available',
        ])->toArray();
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

        if (! app()->environment('local')) {
            Validator::make([
                'recaptchaToken' => $this->recaptchaToken,
            ], [
                'recaptchaToken' => 'required|captcha',
            ])->validate();
        }

        $transaction = null;

        DB::transaction(function () use (&$transaction) {
            $booking = Booking::create([
                'transaction_number' => $this->generateTransactionNumber(),
                'origin' => $this->origin,
                'destination' => $this->destination,
                'departure_date' => $this->departure_date,
                'return_date' => $this->return_date,
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

            $booking->load('passengers.discount', 'accommodations', 'transaction');

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
                'selected_schedule_id' => 'required|integer',
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
            'selected_schedule_id' => 'required|integer',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'infants' => 'required|integer|min:0',
            'passengers.*.discount_id' => 'nullable|exists:discounts,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'recaptchaToken' => $this->recaptchaRule(),
        ];
    }

    protected function generateTransactionNumber(): string
    {
        return 'AGT-' . now()->format('Ymd') . '-' . rand(1000, 9999);
    }

    protected function calculateTotalPrice(): float
    {
        $selectedIds = array_keys(array_filter($this->selected_accommodation_ids));

        $accommodationsTotal = $this->accommodationCatalog
            ->whereIn('id', $selectedIds)
            ->sum(fn ($item) => floatval($item->price));

        $settings = PaymentSetting::current();

        // Service fee: charged per traveler (infants excluded) and per selected accommodation.
        $payingTravelers = collect($this->passengers)->where('type', '!=', 'infant')->count();
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person))
            + (count($selectedIds) * floatval($settings->fee_per_accommodation));

        return $accommodationsTotal + $serviceFee;
    }

    protected function recaptchaRule(): string
    {
        return app()->environment('local') ? 'nullable|string' : 'required|string';
    }
}
