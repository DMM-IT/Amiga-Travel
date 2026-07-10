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
    public string $trip_type = 'one_way';
    public string $mode = '';
    public string $origin = '';
    public string $destination = '';
    public ?string $departure_date = null;
    public ?string $return_date = null;
    public int $adults = 1;
    public int $children = 0;
    public ?int $selected_schedule_id = null;
    public bool $showPassengerInfoModal = false;
    public bool $showPwdTypeModal = false;
    public ?int $pwdTypeModalPassengerIndex = null;
    public string $pwd_disability_other_tmp = '';

    // Each entry: ['type' => 'adult'|'child', 'name' => '', 'discount_id' => null]
    public array $passengers = [];

    // Selected catalog accommodation ids, e.g. [3 => true, 5 => true]
    public array $selected_accommodation_ids = [];

    public string $client_name = '';
    public string $client_email = '';
    public string $recaptchaToken = '';
    public \Illuminate\Support\Collection $discounts;
    public \Illuminate\Support\Collection $accommodationCatalog;
    public array $availableSchedules = [];

    public function mount(): void
    {
        $this->discounts = Discount::orderBy('name')->get();
        $this->accommodationCatalog = Accommodation::where('is_active', true)->orderBy('name')->get();
        $this->availableSchedules = [];
        $this->syncPassengerEntries();
    }

    #[Computed]
    public function origins(): array
    {
        if (blank($this->mode)) {
            return [];
        }

        return FerryRoute::activeOrigins($this->mode);
    }

    #[Computed]
    public function destinations(): array
    {
        if (blank($this->origin)) {
            return [];
        }

        return FerryRoute::activeDestinationsFor($this->origin, $this->mode);
    }

    public function updatedTripType(string $value): void
    {
        $this->trip_type = $value;
        $this->return_date = null;
    }

    public function setTripType(string $type): void
    {
        if (! in_array($type, ['one_way', 'round_trip'], true)) {
            return;
        }

        $this->trip_type = $type;
        $this->return_date = null;
    }

    protected $listeners = ['datePickerUpdated'];

    public function updatedMode(string $value): void
    {
        $this->mode = $value;
        $this->origin = '';
        $this->destination = '';
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
    }

    public function datePickerUpdated(string $field, ?string $value): void
    {
        if (! in_array($field, ['departure_date', 'return_date'], true)) {
            return;
        }

        $this->$field = $value;

        if ($field === 'departure_date') {
            $this->selected_schedule_id = null;
            $this->availableSchedules = [];
        }

        $this->validateOnly($field, $this->allRules());
    }

    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'passengers.')) {
            if (preg_match('/^passengers\.(\d+)\.(first_name|middle_name|last_name)$/', $propertyName, $matches)) {
                $this->syncFullPassengerNames();
            }

            if (preg_match('/^passengers\.(\d+)\.pwd_disability_type$/', $propertyName, $matches)) {
                $index = intval($matches[1]);
                if (($this->passengers[$index]['pwd_disability_type'] ?? '') === 'other') {
                    $this->openPwdTypeModal($index);
                }
            }

            return;
        }

        if (str_starts_with($propertyName, 'selected_accommodation_ids')) {
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

        if ($this->step === 4) {
            $this->validatePassengerExtras();
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
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date, $this->mode)
            ->get()
            ->map(fn (Schedule $schedule) => $schedule->toBookingArray())
            ->values()
            ->all();
    }

    /**
     * Build (or resize) the per-passenger entries based on the adult/child
     * counts entered in step 3, preserving names/discounts already typed in for
     * passengers that still exist after a count change.
     */
    protected function syncPassengerEntries(): void
    {
        $existingByType = collect($this->passengers)->groupBy('type');

        $rebuilt = [];

        foreach (['adult' => $this->adults, 'child' => $this->children] as $type => $count) {
            $existing = $existingByType->get($type, collect())->values();

            for ($i = 0; $i < $count; $i++) {
                $passenger = $existing->get($i, [
                    'type' => $type,
                    'name' => '',
                    'first_name' => '',
                    'middle_name' => '',
                    'last_name' => '',
                    'discount_id' => null,
                ]);

                $nameParts = $this->passengerNameParts($passenger);

                $rebuilt[] = array_merge([
                    'type' => $type,
                    'name' => $passenger['name'] ?? '',
                    'first_name' => $nameParts['first_name'],
                    'middle_name' => $nameParts['middle_name'],
                    'last_name' => $nameParts['last_name'],
                    'discount_id' => $passenger['discount_id'] ?? null,
                ], $passenger);
            }
        }

        $this->passengers = $rebuilt;
    }

    protected function passengerNameParts(array $passenger): array
    {
        $first = trim($passenger['first_name'] ?? '');
        $middle = trim($passenger['middle_name'] ?? '');
        $last = trim($passenger['last_name'] ?? '');

        if ($first === '' && $middle === '' && $last === '' && ! empty($passenger['name'])) {
            $words = preg_split('/\s+/', trim($passenger['name']));
            $first = $words[0] ?? '';

            if (count($words) === 1) {
                $last = '';
            } elseif (count($words) === 2) {
                $last = $words[1];
            } else {
                $last = array_pop($words);
                array_shift($words);
                $middle = trim(implode(' ', $words));
            }
        }

        return [
            'first_name' => $first,
            'middle_name' => $middle,
            'last_name' => $last,
        ];
    }

    protected function syncFullPassengerNames(): void
    {
        foreach ($this->passengers as $index => $passenger) {
            $first = trim($passenger['first_name'] ?? '');
            $middle = trim($passenger['middle_name'] ?? '');
            $last = trim($passenger['last_name'] ?? '');

            $this->passengers[$index]['name'] = trim(implode(' ', array_filter([$first, $middle, $last], fn ($value) => $value !== '')));
        }
    }

    public function toggleAccommodation(int $accommodationId): void
    {
        if (isset($this->selected_accommodation_ids[$accommodationId])) {
            unset($this->selected_accommodation_ids[$accommodationId]);
        } else {
            $this->selected_accommodation_ids[$accommodationId] = true;
        }
    }

    public function openPwdTypeModal(int $index): void
    {
        $this->pwdTypeModalPassengerIndex = $index;
        $this->pwd_disability_other_tmp = $this->passengers[$index]['pwd_disability_other'] ?? '';
        $this->showPwdTypeModal = true;
    }

    public function togglePwdTypeModal(): void
    {
        $this->showPwdTypeModal = ! $this->showPwdTypeModal;
        if (! $this->showPwdTypeModal) {
            $this->pwdTypeModalPassengerIndex = null;
        }
    }

    public function savePwdDisabilityOther(): void
    {
        if ($this->pwdTypeModalPassengerIndex === null) {
            return;
        }

        $index = $this->pwdTypeModalPassengerIndex;
        $this->passengers[$index]['pwd_disability_other'] = $this->pwd_disability_other_tmp;
        $this->showPwdTypeModal = false;
        $this->pwdTypeModalPassengerIndex = null;
    }

    public function submit()
    {
        $this->validate($this->allRules());
        $this->validatePassengerExtras();
        $this->assertSelectedScheduleIsValid();

        if (! app()->environment('local')) {
            Validator::make([
                'recaptchaToken' => $this->recaptchaToken,
            ], [
                'recaptchaToken' => 'required|captcha',
            ])->validate();
        }

        $schedule = Schedule::query()
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date, $this->mode)
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
                'trip_type' => 'required|string|in:one_way,round_trip',
                'mode' => 'required|string|in:ferry,airline',
                'origin' => 'required|string|max:255',
                'destination' => 'required|string|max:255',
                'departure_date' => 'required|date',
                'return_date' => $this->trip_type === 'round_trip' ? 'required|date|after_or_equal:departure_date' : 'nullable|date|after_or_equal:departure_date',
            ],
            2 => [
                'selected_schedule_id' => 'required|integer|exists:schedules,id',
            ],
            3 => [
                'adults' => [
                    'required',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        if ($value + $this->children > 8) {
                            $fail('Maximum of 8 passengers per booking.');
                        }
                    },
                ],
                'children' => [
                    'required',
                    'integer',
                    'min:0',
                    function ($attribute, $value, $fail) {
                        if ($value + $this->adults > 8) {
                            $fail('Maximum of 8 passengers per booking.');
                        }
                    },
                ],
            ],
            4 => [
                'passengers.*.first_name' => 'required|string|max:255',
                'passengers.*.middle_name' => 'nullable|string|max:255',
                'passengers.*.last_name' => 'required|string|max:255',
                'passengers.*.name' => 'required|string|max:255',
                'passengers.*.discount_id' => 'nullable|exists:discounts,id',
                'passengers.*.pwd_disability_type' => 'nullable|string|max:255',
                'passengers.*.pwd_disability_other' => 'nullable|string|max:255',
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
            'trip_type' => 'required|string|in:one_way,round_trip',
            'mode' => 'required|string|in:ferry,airline',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => $this->trip_type === 'round_trip' ? 'required|date|after_or_equal:departure_date' : 'nullable|date|after_or_equal:departure_date',
            'selected_schedule_id' => 'required|integer|exists:schedules,id',
            'adults' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value + $this->children > 8) {
                        $fail('Maximum of 8 passengers per booking.');
                    }
                },
            ],
            'children' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($value + $this->adults > 8) {
                        $fail('Maximum of 8 passengers per booking.');
                    }
                },
            ],
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.middle_name' => 'nullable|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.discount_id' => 'nullable|exists:discounts,id',
            'passengers.*.pwd_disability_type' => 'nullable|string|max:255',
            'passengers.*.pwd_disability_other' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'recaptchaToken' => $this->recaptchaRule(),
        ];
    }

    protected function assertSelectedScheduleIsValid(): void
    {
        if (! $this->selected_schedule_id) {
            throw ValidationException::withMessages([
                'selected_schedule_id' => 'Please select a schedule.',
            ]);
        }

        $isValid = Schedule::query()
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date, $this->mode)
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
        $tripMultiplier = $this->trip_type === 'round_trip' ? 2 : 1;
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

        // Service fee: charged per traveler and per selected accommodation.
        $payingTravelers = count($this->passengers);
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person))
            + (count($selectedIds) * floatval($settings->fee_per_accommodation));

        return $ferryTotal + $accommodationsTotal + $serviceFee;
    }

    public function incrementAdults(): void
    {
        if ($this->adults + $this->children >= 8) {
            return;
        }

        $this->adults++;
    }

    public function decrementAdults(): void
    {
        if ($this->adults <= 1) {
            return;
        }

        $this->adults--;
    }

    public function incrementChildren(): void
    {
        if ($this->adults + $this->children >= 8) {
            return;
        }

        $this->children++;
    }

    public function decrementChildren(): void
    {
        if ($this->children <= 0) {
            return;
        }

        $this->children--;
    }

    protected function validatePassengerExtras(): void
    {
        $validator = Validator::make([
            'passengers' => $this->passengers,
        ], [
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.middle_name' => 'nullable|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.discount_id' => 'nullable|exists:discounts,id',
            'passengers.*.pwd_disability_type' => 'nullable|string|max:255',
            'passengers.*.pwd_disability_other' => 'nullable|string|max:255',
        ]);

        $validator->after(function ($validator) {
            foreach ($this->passengers as $index => $passenger) {
                $discount = $this->discounts->firstWhere('id', $passenger['discount_id']);

                if (! $discount) {
                    continue;
                }

                $discountKey = strtolower($discount->name);

                if (str_contains($discountKey, 'student')) {
                    if (blank($passenger['student_school'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.student_school", 'School name is required when Student discount is selected.');
                    }

                    if (blank($passenger['student_number'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.student_number", 'Student number is required when Student discount is selected.');
                    }
                }

                if (str_contains($discountKey, 'senior')) {
                    if (blank($passenger['senior_dob'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.senior_dob", 'Date of birth is required when Senior Citizen discount is selected.');
                    }

                    if (blank($passenger['senior_osca_number'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.senior_osca_number", 'OSCA number is required when Senior Citizen discount is selected.');
                    }
                }

                if (str_contains($discountKey, 'pwd')) {
                    $type = $passenger['pwd_disability_type'] ?? null;

                    if (blank($type)) {
                        $validator->errors()->add("passengers.{$index}.pwd_disability_type", 'Type of disability is required when PWD Card discount is selected.');
                    }

                    if ($type === 'other' && blank($passenger['pwd_disability_other'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.pwd_disability_other", 'Please specify the disability type when Others is selected.');
                    }

                    if (blank($passenger['pwd_id_number'] ?? null)) {
                        $validator->errors()->add("passengers.{$index}.pwd_id_number", 'PWD ID number is required when PWD Card discount is selected.');
                    }
                }
            }
        });

        $validator->validate();
    }

    public function togglePassengerInfoModal(): void
    {
        $this->showPassengerInfoModal = ! $this->showPassengerInfoModal;
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
