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
use App\Models\ScheduleAccommodation;
use App\Models\TransportClass;
use App\Models\VehicleRate;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\LaravelPdf\Facades\Pdf;

class BookingForm extends Component
{
    use WithFileUploads;
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
    public bool $showModeDropdown = false;
    public bool $showOriginDropdown = false;
    public bool $showDestinationDropdown = false;
    public string $originSearch = '';
    public string $destinationSearch = '';
    public ?int $pwdTypeModalPassengerIndex = null;
    public string $pwd_disability_other_tmp = '';

    // Each entry: ['type' => 'adult'|'child', 'name' => '', 'discount_id' => null]
    public array $passengers = [];
    public array $studentIdProofs = [];

    protected $validationAttributes = [
        'passengers.*.first_name' => 'first name',
        'passengers.*.middle_name' => 'middle name',
        'passengers.*.last_name' => 'last name',
        'passengers.*.name' => 'full name',
        'passengers.*.student_number' => 'student number',
        'studentIdProofs.*' => 'school ID proof',
        'passengers.*.senior_dob' => 'date of birth',
        'passengers.*.senior_osca_number' => 'OSCA number',
        'passengers.*.pwd_disability_type' => 'type of disability',
        'passengers.*.pwd_disability_other' => 'disability details',
        'passengers.*.pwd_id_number' => 'PWD ID number',
        'vehicle_type' => 'vehicle type',
        'vehicle_plate_number' => 'plate number',
        'vehicle_price' => 'vehicle price',
    ];

    // Selected schedule accommodation id
    public ?int $selected_schedule_accommodation_id = null;
    public ?int $selected_transport_class_id = null;
    public ?int $selectingSeatForPassengerIndex = null;

    // Car booking fields
    public bool $has_vehicle = false;
    public ?int $selected_vehicle_rate_id = null;
    public string $vehicle_type = '';
    public string $vehicle_plate_number = '';
    public ?float $vehicle_price = null;

    public string $client_name = '';
    public string $client_email = '';
    public string $recaptchaToken = '';
    public \Illuminate\Support\Collection $discounts;
    public \Illuminate\Support\Collection $transportClassCatalog;
    public \Illuminate\Support\Collection $vehicleRateCatalog;
    public \Illuminate\Support\Collection $accommodationCatalog;
    public ?int $selected_hotel_id = null;
    public array $availableSchedules = [];

    public function mount(): void
    {
        $this->discounts = Discount::orderBy('name')->get();
        $this->transportClassCatalog = TransportClass::where('is_active', true)->orderBy('name')->get();
        $this->vehicleRateCatalog = VehicleRate::where('is_active', true)->orderBy('sort_order')->get();
        $this->accommodationCatalog = Accommodation::where('is_active', true)->orderBy('name')->get();
        $this->availableSchedules = [];

        if (session()->has('booking_draft')) {
            $draft = session('booking_draft', []);

            foreach ($draft as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }

            if (! blank($this->origin) && ! blank($this->destination) && ! blank($this->departure_date)) {
                $this->availableSchedules = $this->getAvailableSchedules();
            }
        }

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

    #[Computed]
    public function filteredOrigins(): array
    {
        if (blank($this->originSearch)) {
            return $this->origins;
        }

        return collect($this->origins)
            ->filter(fn ($item) => str_starts_with(strtolower($item), strtolower($this->originSearch)))
            ->values()
            ->all();
    }

    #[Computed]
    public function filteredDestinations(): array
    {
        if (blank($this->destinationSearch)) {
            return $this->destinations;
        }

        return collect($this->destinations)
            ->filter(fn ($item) => str_starts_with(strtolower($item), strtolower($this->destinationSearch)))
            ->values()
            ->all();
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

    protected $listeners = [
        'datePickerUpdated',
        'dropdownOpened' => 'onDropdownOpened',
    ];

    public function updatedMode(string $value): void
    {
        $this->mode = $value;
        $this->origin = '';
        $this->destination = '';
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        $this->resetVehicleData();
    }

    public function getModeOptions(): array
    {
        return [
            'ferry' => 'Ferry',
            'airline' => 'Airline',
        ];
    }

    public function toggleModeDropdown(): void
    {
        $this->showModeDropdown = ! $this->showModeDropdown;
        if ($this->showModeDropdown) {
            $this->showOriginDropdown = false;
            $this->showDestinationDropdown = false;
            $this->dispatch('dropdownOpened', 'mode');
        }
    }

    public function onDropdownOpened($name = null): void
    {
        // If another dropdown opened and it's not one of BookingForm's, close ours.
        if ($name === null) {
            $this->showModeDropdown = false;
            $this->showOriginDropdown = false;
            $this->showDestinationDropdown = false;
            return;
        }

        if ($name !== 'mode') {
            $this->showModeDropdown = false;
        }
        if ($name !== 'origin') {
            $this->showOriginDropdown = false;
        }
        if ($name !== 'destination') {
            $this->showDestinationDropdown = false;
        }
    }

    public function selectMode(string $mode): void
    {
        if (! array_key_exists($mode, $this->getModeOptions())) {
            return;
        }

        $this->mode = $mode;
        $this->origin = '';
        $this->destination = '';
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        $this->resetVehicleData();
        $this->showModeDropdown = false;
    }

    protected function resetVehicleData(): void
    {
        if ($this->mode === 'airline') {
            $this->has_vehicle = false;
            $this->selected_vehicle_rate_id = null;
            $this->vehicle_type = '';
            $this->vehicle_plate_number = '';
            $this->vehicle_price = null;
        }
    }

    public function toggleOriginDropdown(): void
    {
        $this->showOriginDropdown = ! $this->showOriginDropdown;

        if ($this->showOriginDropdown) {
            $this->showModeDropdown = false;
            $this->showDestinationDropdown = false;
            $this->dispatch('dropdownOpened', 'origin');
        }

        if (! $this->showOriginDropdown) {
            $this->originSearch = '';
        }
    }

    public function toggleDestinationDropdown(): void
    {
        $this->showDestinationDropdown = ! $this->showDestinationDropdown;

        if ($this->showDestinationDropdown) {
            $this->showModeDropdown = false;
            $this->showOriginDropdown = false;
            $this->dispatch('dropdownOpened', 'destination');
        }

        if (! $this->showDestinationDropdown) {
            $this->destinationSearch = '';
        }
    }

    public function selectOrigin(string $origin): void
    {
        $this->origin = $origin;
        $this->destination = '';
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        $this->showOriginDropdown = false;
        $this->originSearch = '';
    }

    public function selectDestination(string $destination): void
    {
        $this->destination = $destination;
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        $this->showDestinationDropdown = false;
        $this->destinationSearch = '';

        $this->saveDraft();
    }

    public function updatedOriginSearch(): void
    {
        $this->showOriginDropdown = true;
        $this->showModeDropdown = false;
        $this->showDestinationDropdown = false;
        $this->dispatch('dropdownOpened', 'origin');
    }

    public function updatedDestinationSearch(): void
    {
        $this->showDestinationDropdown = true;
        $this->showModeDropdown = false;
        $this->showOriginDropdown = false;
        $this->dispatch('dropdownOpened', 'destination');
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

            $this->saveDraft();

            return;
        }

        if (str_starts_with($propertyName, 'selected_accommodation_ids')) {
            $this->saveDraft();

            return;
        }

        if (in_array($propertyName, ['has_vehicle', 'selected_vehicle_rate_id', 'vehicle_type', 'vehicle_plate_number', 'vehicle_price'], true)) {
            $this->saveDraft();

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
        $this->saveDraft();
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

        if ($this->step === 1) {
            $this->syncPassengerEntries();
        }

        if ($this->step === 2) {
            $this->assertSelectedScheduleIsValid();
        }

        if ($this->step === 3) {
            $this->validatePassengerExtras();
        }

        if ($this->step < 5) {
            $this->step++;
        }

        $this->saveDraft();
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
        $this->selected_transport_class_id = null;
        $this->selectingSeatForPassengerIndex = null;

        foreach ($this->passengers as $index => $passenger) {
            $this->passengers[$index]['seat_number'] = null;
            $this->passengers[$index]['seat_row'] = null;
            $this->passengers[$index]['seat_section'] = null;
        }

        $this->saveDraft();
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
                    'seat_number' => null,
                    'seat_row' => null,
                    'seat_section' => null,
                ]);

                $nameParts = $this->passengerNameParts($passenger);

                $rebuilt[] = array_merge([
                    'type' => $type,
                    'name' => $passenger['name'] ?? '',
                    'first_name' => $nameParts['first_name'],
                    'middle_name' => $nameParts['middle_name'],
                    'last_name' => $nameParts['last_name'],
                    'discount_id' => $passenger['discount_id'] ?? null,
                    'seat_number' => $passenger['seat_number'] ?? null,
                    'seat_row' => $passenger['seat_row'] ?? null,
                    'seat_section' => $passenger['seat_section'] ?? null,
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

    public function updatedHasVehicle(bool $value): void
    {
        if (! $value) {
            $this->selected_vehicle_rate_id = null;
            $this->vehicle_type = '';
            $this->vehicle_plate_number = '';
            $this->vehicle_price = null;
        }

        $this->saveDraft();
    }

    public function updatedSelectedVehicleRateId($value): void
    {
        if (blank($value)) {
            $this->vehicle_type = '';
            $this->vehicle_price = null;

            return;
        }

        $rate = $this->vehicleRateCatalog->firstWhere('id', (int) $value);

        if ($rate) {
            $this->vehicle_type = $rate->name;
            $this->vehicle_price = floatval($rate->price);
        }

        $this->saveDraft();
    }

    public function selectScheduleAccommodation(int $accommodationId): void
    {
        if ($this->selected_schedule_accommodation_id === $accommodationId) {
            $this->selected_schedule_accommodation_id = null;
        } else {
            $this->selected_schedule_accommodation_id = $accommodationId;
        }
        $this->saveDraft();
    }

    public function selectSeatForPassenger(string $seat): void
    {
        // Find the passenger to assign the seat to
        $indexToAssign = $this->selectingSeatForPassengerIndex;
        if ($indexToAssign === null) {
            // Find first passenger without a seat
            foreach ($this->passengers as $idx => $passenger) {
                if (empty($passenger['seat_number'])) {
                    $indexToAssign = $idx;
                    break;
                }
            }
        }

        if ($indexToAssign !== null) {
            // Get selected transport class name for seat section
            $seatSection = 'Economy';
            if ($this->selected_transport_class_id) {
                $selectedClass = $this->transportClassCatalog->firstWhere('id', $this->selected_transport_class_id);
                if ($selectedClass) {
                    $seatSection = $selectedClass->name;
                }
            }

            $this->passengers[$indexToAssign]['seat_number'] = $seat;
            $this->passengers[$indexToAssign]['seat_row'] = preg_replace('/[^0-9]/', '', $seat);
            $this->passengers[$indexToAssign]['seat_section'] = $seatSection;
            $this->selectingSeatForPassengerIndex = null;
            $this->saveDraft();
        }
    }

    public function selectTransportClass(?int $classId): void
    {
        $this->selected_transport_class_id = $this->selected_transport_class_id === $classId ? null : $classId;
        $this->selectingSeatForPassengerIndex = null;

        foreach ($this->passengers as $index => $passenger) {
            $this->passengers[$index]['seat_number'] = null;
            $this->passengers[$index]['seat_row'] = null;
            $this->passengers[$index]['seat_section'] = null;
        }

        $this->saveDraft();
    }

    public function chooseSeatForPassenger(int $index): void
    {
        $this->selectingSeatForPassengerIndex = $index;
        $this->saveDraft();
    }

    public function clearSeatForPassenger(int $index): void
    {
        $this->passengers[$index]['seat_number'] = null;
        $this->passengers[$index]['seat_row'] = null;
        $this->passengers[$index]['seat_section'] = null;
        $this->selectingSeatForPassengerIndex = $index;
        $this->saveDraft();
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
        session()->forget('booking_draft');

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

        $scheduleAccommodation = $this->selected_schedule_accommodation_id
            ? ScheduleAccommodation::find($this->selected_schedule_accommodation_id)
            : null;

        $transaction = null;

        DB::transaction(function () use (&$transaction, $schedule, $scheduleAccommodation) {
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
                'schedule_accommodation_id' => $scheduleAccommodation?->id,
                'schedule_accommodation_name' => $scheduleAccommodation?->name,
                'schedule_accommodation_price' => $scheduleAccommodation?->price,
                'client_name' => $this->client_name,
                'client_email' => $this->client_email,
                'total_price' => $this->calculateTotalPrice(),
                'status' => 'pending',
                'has_vehicle' => $this->has_vehicle,
                'vehicle_type' => $this->vehicle_type,
                'vehicle_plate_number' => $this->vehicle_plate_number,
                'vehicle_price' => $this->vehicle_price,
            ]);

            foreach ($this->passengers as $passenger) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => $passenger['type'],
                    'name' => $passenger['name'] ?: null,
                    'discount_id' => $passenger['discount_id'] ?: null,
                    'seat_number' => $passenger['seat_number'] ?? null,
                    'seat_row' => $passenger['seat_row'] ?? null,
                    'seat_section' => $passenger['seat_section'] ?? null,
                ]);
            }

            if ($this->selected_transport_class_id) {
                $transportClass = TransportClass::find($this->selected_transport_class_id);
                if ($transportClass) {
                    $booking->transportClasses()->attach($transportClass->id, [
                        'price' => $transportClass->price,
                    ]);
                }
            }

            if ($this->selected_hotel_id) {
                $hotel = Accommodation::find($this->selected_hotel_id);
                if ($hotel) {
                    $booking->accommodations()->attach($hotel->id, [
                        'price' => $hotel->price,
                    ]);
                }
            }

            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_status' => 'unpaid',
            ]);

            $booking->load('passengers.discount', 'scheduleAccommodation', 'transportClasses', 'transaction', 'schedule');

            $receiptPath = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');
            if (! file_exists(dirname($receiptPath))) {
                mkdir(dirname($receiptPath), 0755, true);
            }

            Pdf::driver('dompdf')
                ->view('pdf.receipt', ['booking' => $booking])
                ->save($receiptPath);
        });

        return redirect()->route('payment.show', $transaction);
    }

    public function render()
    {
        return view('livewire.booking-form');
    }

    protected function saveDraft(): void
    {
        session(['booking_draft' => [
            'step' => $this->step,
            'trip_type' => $this->trip_type,
            'mode' => $this->mode,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departure_date' => $this->departure_date,
            'return_date' => $this->return_date,
            'adults' => $this->adults,
            'children' => $this->children,
            'selected_schedule_id' => $this->selected_schedule_id,
            'passengers' => $this->passengers,
            'selected_schedule_accommodation_id' => $this->selected_schedule_accommodation_id,
            'selected_transport_class_id' => $this->selected_transport_class_id,
            'has_vehicle' => $this->has_vehicle,
            'selected_vehicle_rate_id' => $this->selected_vehicle_rate_id,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_plate_number' => $this->vehicle_plate_number,
            'vehicle_price' => $this->vehicle_price,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'selected_hotel_id' => $this->selected_hotel_id,
        ]]);
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
                'has_vehicle' => 'boolean',
                'vehicle_type' => 'required_if:has_vehicle,true|nullable|string|max:255',
                'vehicle_plate_number' => 'required_if:has_vehicle,true|nullable|string|max:255',
                'vehicle_price' => 'required_if:has_vehicle,true|nullable|numeric|min:0',
            ],
            2 => [
                'selected_schedule_id' => 'required|integer|exists:schedules,id',
            ],
            3 => [
                'passengers.*.first_name' => 'required|string|max:255',
                'passengers.*.middle_name' => 'nullable|string|max:255',
                'passengers.*.last_name' => 'required|string|max:255',
                'passengers.*.name' => 'required|string|max:255',
                'passengers.*.discount_id' => 'nullable|exists:discounts,id',
                'passengers.*.pwd_disability_type' => 'nullable|string|max:255',
                'passengers.*.pwd_disability_other' => 'nullable|string|max:255',
                'passengers.*.pwd_id_number' => 'nullable|string|max:255',
                'studentIdProofs.*' => 'nullable|image|max:2048',
            ],
            4 => [],
            5 => [
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
            'studentIdProofs.*' => 'nullable|image|max:2048',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'recaptchaToken' => $this->recaptchaRule(),
            'has_vehicle' => 'boolean',
            'vehicle_type' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_plate_number' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_price' => 'required_if:has_vehicle,true|nullable|numeric|min:0',
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
        $baseSchedulePrice = $this->getSelectedSchedulePrice();
        $scheduleAccommodationPrice = $this->getSelectedScheduleAccommodationPrice();
        $tripMultiplier = $this->trip_type === 'round_trip' ? 2 : 1;
        $discountsById = $this->discounts->keyBy('id');

        $transportTotal = collect($this->passengers)->sum(function (array $passenger) use ($baseSchedulePrice, $scheduleAccommodationPrice, $tripMultiplier, $discountsById) {
            $fare = ($baseSchedulePrice + $scheduleAccommodationPrice) * $tripMultiplier;

            if (! empty($passenger['discount_id'])) {
                $discount = $discountsById->get($passenger['discount_id']);

                if ($discount) {
                    $fare -= $fare * (floatval($discount->percentage) / 100);
                }
            }

            return $fare;
        });

        $transportClassTotal = $this->selected_transport_class_id
            ? floatval($this->transportClassCatalog->firstWhere('id', $this->selected_transport_class_id)->price ?? 0)
            : 0;

        $vehicleTotal = $this->has_vehicle ? floatval($this->vehicle_price ?? 0) : 0;

        $hotelTotal = $this->selected_hotel_id
            ? floatval($this->accommodationCatalog->firstWhere('id', $this->selected_hotel_id)->price ?? 0)
            : 0;

        $settings = PaymentSetting::current();

        // Service fee: charged per traveler
        $payingTravelers = count($this->passengers);
        $serviceFee = ($payingTravelers * floatval($settings->fee_per_person));

        return $transportTotal + $transportClassTotal + $vehicleTotal + $hotelTotal + $serviceFee;
    }

    protected function getSelectedScheduleAccommodationPrice(): float
    {
        if (! $this->selected_schedule_accommodation_id || ! $this->selected_schedule_id) {
            return 0;
        }

        $schedule = collect($this->availableSchedules)
            ->firstWhere('id', $this->selected_schedule_id);

        if ($schedule && isset($schedule['accommodations'])) {
            $accommodation = collect($schedule['accommodations'])
                ->firstWhere('id', $this->selected_schedule_accommodation_id);
            if ($accommodation) {
                return floatval($accommodation['price']);
            }
        }

        return 0;
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
                    if (blank($this->studentIdProofs[$index] ?? null)) {
                        $validator->errors()->add("studentIdProofs.{$index}", 'School ID proof is required when Student discount is selected.');
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
