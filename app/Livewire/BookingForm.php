<?php

namespace App\Livewire;

use App\Mail\BookingConfirmation;
use App\Mail\BookingCreated;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\FerryRoute;
use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Passenger;
use App\Models\PaymentSetting;
use App\Models\Schedule;
use App\Models\ScheduleAccommodation;
use App\Models\TransportClass;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
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
use Carbon\Carbon;
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
    public ?int $duration_days = null;
    public array $available_package_dates = [];
    public array $available_schedule_dates = [];
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
    public ?string $operator = null;

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

    public ?int $tour_id = null;
    public ?int $tour_date_id = null;
    public ?Tour $tour = null;
    public ?TourDate $selectedTourDate = null;

    // Prefilled package info (from CSV)
    public string $package_name = '';
    public string $package_price = '';
    public bool $prefilled_from_package = false;

    // Car booking fields
    public bool $has_vehicle = false;
    public string $vehicle_booking_method = 'category';
    public ?int $selected_vehicle_rate_id = null;
    public ?int $selected_brand_id = null;
    public ?int $selected_model_id = null;
    public string $vehicle_type = '';
    public string $vehicle_plate_number = '';
    public ?float $vehicle_price = null;
    public string $driver_name = '';
    public ?string $driver_birthday = null;
    public bool $showBaggageRules = false;
    public \Illuminate\Support\Collection $vehicleBrandCatalog;
    public \Illuminate\Support\Collection $vehicleModelCatalog;

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
        $this->discounts = Discount::all()->sortBy('name')->values();
        $this->transportClassCatalog = TransportClass::query()->where('is_active', true)->orderBy('name')->get();
        $this->vehicleRateCatalog = VehicleRate::query()->where('is_active', true)->orderBy('sort_order')->get();
        $this->vehicleBrandCatalog = VehicleBrand::query()->where('is_active', true)->orderBy('sort_order')->get();
        $this->vehicleModelCatalog = collect();
        $this->accommodationCatalog = Accommodation::query()->where('is_active', true)->orderBy('name')->get();
        $this->availableSchedules = [];

        if ($this->selected_brand_id) {
            $this->vehicleModelCatalog = VehicleModel::query()
                ->where('vehicle_brand_id', $this->selected_brand_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        // Check if we have tour/package query params first
        $allowed = [
            'trip_type','mode','operator','origin','destination','departure_date','return_date','duration_days','adults','children',
            'client_name','client_email','selected_hotel','selected_hotel_id','hotel','package_name','price','tour_id','tour_date_id'
        ];
        $hasPackageQueryParams = ! empty(array_intersect(array_keys(request()->query()), $allowed));

        // If we have package/tour query params, ignore session draft entirely; otherwise load draft first
        $hasSessionDraft = session()->has('booking_draft');
        if (!$hasPackageQueryParams && $hasSessionDraft) {
            $draft = session('booking_draft', []);
            foreach ($draft as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        } else {
            // If we have package params, clear the draft to avoid conflicts
            session()->forget('booking_draft');
        }

        if ($this->selected_brand_id) {
            $this->vehicleModelCatalog = VehicleModel::query()
                ->where('vehicle_brand_id', $this->selected_brand_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        // Now apply tour/package query params
        // Pre-fill tour if present in query params
        $reqTour = request()->query('tour_id');
        $reqTourDate = request()->query('tour_date_id');
        if ($reqTour) {
            $this->tour_id = intval($reqTour);
            $this->tour = Tour::with('dates')->find($this->tour_id);

            if ($this->tour) {
                // Prefill route/mode from tour if provided
                if ($this->tour->mode) {
                    $this->mode = $this->tour->mode;
                }

                if ($this->tour->origin) {
                    $this->origin = $this->tour->origin;
                }

                if ($this->tour->destination) {
                    $this->destination = $this->tour->destination;
                }
                
                // Set duration days from the tour!
                $this->duration_days = $this->tour->duration_days;
                
                // If a tour date was passed, preselect it; otherwise allow the user to pick from tour dates
                if ($reqTourDate) {
                    $this->tour_date_id = intval($reqTourDate);
                    $this->selectedTourDate = $this->tour->dates->firstWhere('id', $this->tour_date_id) ?: TourDate::find($this->tour_date_id);

                    if ($this->selectedTourDate) {
                        $this->departure_date = Carbon::parse($this->selectedTourDate->date)->format('Y-m-d');
                        $this->return_date = Carbon::parse($this->selectedTourDate->date)->addDays($this->tour->duration_days - 1)->format('Y-m-d');
                    }
                }
            }
        }

        // Prefill other booking fields from query params
        foreach (request()->query() as $key => $value) {
            if (in_array($key, $allowed, true) && property_exists($this, $key)) {
                // cast ints where appropriate
                if (in_array($key, ['adults','children','duration_days'], true)) {
                    $this->{$key} = intval($value);
                } else {
                    $this->{$key} = $value;
                }
            }
        }

        // Mark that the form has been prefilled from a package if any relevant query params exist
        $prefillKeys = array_intersect(array_keys(request()->query()), $allowed);
        if (! empty($prefillKeys)) {
            $this->prefilled_from_package = true;
            // also populate package_name and package_price if present
            $this->package_name = request()->query('package_name', $this->package_name);
            $this->package_price = request()->query('price', $this->package_price);
        }

        // If API passed an available_dates list (comma-separated) or multiple params, parse them into array
        $rawAvailable = request()->query('available_dates');
        if ($rawAvailable) {
            if (is_array($rawAvailable)) {
                $candidates = $rawAvailable;
            } else {
                $candidates = preg_split('/[;,|]+/', $rawAvailable);
            }

            foreach ($candidates as $cand) {
                $cand = trim((string) $cand);
                if ($cand === '') continue;
                try {
                    $dt = Carbon::parse($cand);
                    $iso = $dt->format('Y-m-d');
                    if (! in_array($iso, $this->available_package_dates, true)) {
                        $this->available_package_dates[] = $iso;
                    }
                } catch (\Throwable $e) {
                    // ignore unparseable entries
                }
            }

            // if we parsed dates and no departure_date yet, set the first one
            if (! empty($this->available_package_dates) && empty($this->departure_date)) {
                $this->departure_date = $this->available_package_dates[0];
            }
        }

        // If a duration_days param was provided, store duration_days
        $durationDays = request()->query('duration_days');
        if ($durationDays !== null) {
            $this->duration_days = intval($durationDays);
        }

        // For tour packages: force round trip and lock it
        if ($this->prefilled_from_package || $this->tour_id) {
            $this->trip_type = 'round_trip';
        }
        // If the package has duration days and no explicit trip type, assume round trip
        elseif ($this->duration_days > 1 && $this->trip_type === 'one_way') {
            $this->trip_type = 'round_trip';
        }

        // Fallback: if no parsed package dates and no departure_date set, default to next upcoming weekend (Sat or Sun)
        if (empty($this->available_package_dates) && empty($this->departure_date)) {
            $d = Carbon::today();
            $found = null;
            for ($i = 0; $i < 14; $i++) {
                if (in_array($d->dayOfWeekIso, [6, 7], true)) {
                    $found = $d;
                    break;
                }
                $d = $d->addDay();
            }
            if ($found) {
                $this->departure_date = $found->format('Y-m-d');
            }
        }

        // If return_date is missing, compute it from departure_date and duration_days.
        if (empty($this->return_date) && ! empty($this->departure_date) && ! empty($this->duration_days) && $this->duration_days > 1) {
            $this->trip_type = 'round_trip';
            $this->updateReturnDateFromDuration();
        }

        // If hotel name was provided (hotel) but selected_hotel_id not, try to resolve by name
        $hotelName = request()->query('hotel') ?? request()->query('selected_hotel');
        if (! empty($hotelName) && empty($this->selected_hotel_id)) {
            $hotel = Accommodation::query()->where('name', 'like', '%' . trim($hotelName) . '%')->first();
            if ($hotel) {
                $this->selected_hotel_id = $hotel->id;
            }
        }

        // Fetch available schedules if needed
        if (! blank($this->origin) && ! blank($this->destination)) {
            $this->updateAvailableScheduleDates();
            if (! blank($this->departure_date)) {
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

    #[Computed]
    public function operators(): array
    {
        if (blank($this->mode)) {
            return [];
        }

        return FerryRoute::activeOperatorsFor($this->mode);
    }
    
    #[Computed]
    public function baggageRules(): ?array
    {
        if (blank($this->operator)) {
            return null;
        }
        
        $filePath = base_path('baggage-rules.json');
        if (!file_exists($filePath)) {
            return null;
        }
        
        $json = json_decode(file_get_contents($filePath), true);
        $carriers = $json['carriers'] ?? [];
        $meta = $json['meta'] ?? [];
        
        // Normalize operator name to match with possible keys
        $normalizedOperator = strtolower(trim($this->operator));
        
        foreach ($carriers as $carrier) {
            $carrierName = strtolower(trim($carrier['name'] ?? ''));
            $carrierId = strtolower(trim($carrier['id'] ?? ''));
            
            if (str_contains($carrierName, $normalizedOperator) || str_contains($normalizedOperator, $carrierName) || $carrierId === $normalizedOperator) {
                return array_merge($carrier, ['meta' => $meta]);
            }
        }
        
        return null;
    }

    #[Computed]
public function selectedSchedule(): ?array
{
    if (! $this->selected_schedule_id) {
        return null;
    }

    return collect($this->availableSchedules)->firstWhere('id', $this->selected_schedule_id);
}

    public function updatedTripType(string $value): void
    {
        // If it's a tour package, lock to round trip
        if ($this->prefilled_from_package || $this->tour_id) {
            $this->trip_type = 'round_trip';
        } else {
            $this->trip_type = $value;
        }
        
        if ($this->trip_type === 'one_way') {
            $this->return_date = null;
            $this->saveDraft();
            return;
        }
        
        // Try to set from duration first (packages), otherwise set default return date
        if (!$this->updateReturnDateFromDuration() && !empty($this->departure_date) && empty($this->return_date)) {
            try {
                $dt = Carbon::parse($this->departure_date);
                $this->return_date = $dt->addDay()->format('Y-m-d');
            } catch (\Throwable $e) {
                // ignore parse errors
            }
        }
        $this->saveDraft();
    }

    public function setTripType(string $type): void
    {
        if (! in_array($type, ['one_way', 'round_trip'], true)) {
            return;
        }

        // If it's a tour package, lock to round trip
        if ($this->prefilled_from_package || $this->tour_id) {
            $this->trip_type = 'round_trip';
        } else {
            $this->trip_type = $type;
        }
        
        if ($this->trip_type === 'one_way') {
            $this->return_date = null;
            $this->saveDraft();
            return;
        }
        
        // Try to set from duration first (packages), otherwise set default return date
        if (!$this->updateReturnDateFromDuration() && !empty($this->departure_date) && empty($this->return_date)) {
            try {
                $dt = Carbon::parse($this->departure_date);
                $this->return_date = $dt->addDay()->format('Y-m-d');
            } catch (\Throwable $e) {
                // ignore parse errors
            }
        }
        $this->saveDraft();
    }

    public function updatedDepartureDate(?string $value): void
    {
        $this->departure_date = $value;
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        
        // If it's a tour package with duration, recalculate return date
        if (($this->prefilled_from_package || $this->tour_id) && !empty($this->duration_days) && $this->duration_days > 1) {
            $this->updateReturnDateFromDuration();
        }
        
        $this->saveDraft();
    }

    public function updatedDurationDays(): void
    {
        $this->updateReturnDateFromDuration();
    }

    protected function updateReturnDateFromDuration(): bool
    {
        if (empty($this->departure_date) || empty($this->duration_days) || $this->duration_days < 2) {
            return false;
        }

        try {
            $dt = Carbon::parse($this->departure_date);
            $this->return_date = $dt->addDays($this->duration_days - 1)->format('Y-m-d');
            if ($this->trip_type !== 'round_trip') {
                $this->trip_type = 'round_trip';
            }
            return true;
        } catch (\Throwable $e) {
            // ignore parse errors
            return false;
        }
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
        $this->operator = null;
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

        $this->updateAvailableScheduleDates();
        $this->saveDraft();
    }

    protected function updateAvailableScheduleDates(): void
    {
        if ($this->prefilled_from_package || $this->tour_id) {
            $this->available_schedule_dates = [];
            return;
        }

        if (empty($this->mode) || empty($this->origin) || empty($this->destination)) {
            $this->available_schedule_dates = [];
            return;
        }

        $schedules = Schedule::active()
            ->whereHas('ferryRoute', function ($query) {
                $query->where('origin', $this->origin)
                      ->where('destination', $this->destination)
                      ->where('mode', $this->mode)
                      ->where('is_active', true);
                
                if (! empty($this->operator)) {
                    $query->where('operator', $this->operator);
                }
            })
            ->select('departure_time')
            ->get();

        if ($schedules->isEmpty()) {
            $this->available_schedule_dates = [];
            return;
        }

        $dates = $schedules->pluck('departure_time')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->all();

        $this->available_schedule_dates = $dates;
        
        if ($this->departure_date && !in_array($this->departure_date, $this->available_schedule_dates)) {
            $this->departure_date = null;
        }
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
    
    public function updatedOperator(): void
    {
        $this->selected_schedule_id = null;
        $this->availableSchedules = [];
        $this->updateAvailableScheduleDates();
        $this->saveDraft();
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
            $this->updateReturnDateFromDuration();
        }

        $this->validateOnly($field, $this->allRules());
    }

    public function hydrate(): void
    {
        $this->updateReturnDateFromDuration();
    }

    public function updated($propertyName): void
    {
        if ($propertyName === 'trip_type') {
            $this->saveDraft();
            return;
        }
        if ($propertyName === 'tour_date_id') {
            $this->selectedTourDate = $this->tour?->dates->firstWhere('id', $this->tour_date_id) ?: TourDate::find($this->tour_date_id);
            if ($this->selectedTourDate && $this->tour) {
                $this->departure_date = Carbon::parse($this->selectedTourDate->date)->format('Y-m-d');
                $this->return_date = Carbon::parse($this->selectedTourDate->date)->addDays($this->tour->duration_days)->format('Y-m-d');
            }
            $this->saveDraft();
            return;
        }
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

        if ($propertyName === 'departure_date') {
            $this->updateReturnDateFromDuration();
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
            if (! $this->tour_id) {
                $this->availableSchedules = $this->getAvailableSchedules();

                if (empty($this->availableSchedules)) {
                    throw ValidationException::withMessages([
                        'departure_date' => 'No ferry schedules are available for this route on the selected date. Try another date or contact Amiga Gracia Travel Services.',
                    ]);
                }
            }
            $this->syncPassengerEntries();
        }

        if ($this->step === 2 && ! $this->tour_id) {
            $this->assertSelectedScheduleIsValid();
        }

        if ($this->step === 3) {
            $this->validatePassengerExtras();
        }

        if ($this->step < 5) {
            $this->step++;
            if (($this->tour_id || $this->prefilled_from_package) && $this->step === 2) {
                $this->step = 3;
            }
        }

        $this->saveDraft();
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
            if (($this->tour_id || $this->prefilled_from_package) && $this->step === 2) {
                $this->step = 1;
            }
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
            ->with(['ferryRoute', 'transportClasses', 'scheduleAccommodations'])
            ->forRouteAndDate($this->origin, $this->destination, $this->departure_date, $this->mode, $this->operator)
            ->get()
            ->map(fn (Schedule $schedule) => $schedule->toBookingArray($this->departure_date))
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
            $this->selected_brand_id = null;
            $this->selected_model_id = null;
            $this->vehicle_type = '';
            $this->vehicle_plate_number = '';
            $this->vehicle_price = null;
        }

        $this->saveDraft();
    }

    public function updatedVehicleBookingMethod(string $value): void
    {
        if ($value === 'category') {
            $this->selected_brand_id = null;
            $this->selected_model_id = null;
            if ($this->selected_vehicle_rate_id) {
                $this->updatedSelectedVehicleRateId($this->selected_vehicle_rate_id);
            } else {
                $this->vehicle_type = '';
                $this->vehicle_price = null;
            }
        }

        if ($value === 'brand_model') {
            $this->selected_vehicle_rate_id = null;
            $this->vehicle_type = '';
            $this->vehicle_price = null;
        }

        $this->saveDraft();
    }

    public function updatedSelectedBrandId($value): void
    {
        if (blank($value)) {
            $this->vehicleModelCatalog = collect();
            $this->selected_model_id = null;
            $this->vehicle_type = '';
            $this->vehicle_price = null;
            $this->saveDraft();
            return;
        }

        $this->vehicleModelCatalog = VehicleModel::query()
            ->where('vehicle_brand_id', (int) $value)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->selected_model_id = null;
        $this->vehicle_type = '';
        $this->vehicle_price = null;
        $this->saveDraft();
    }

    public function updatedSelectedModelId($value): void
    {
        if (blank($value)) {
            $this->vehicle_type = '';
            $this->vehicle_price = null;
            $this->saveDraft();
            return;
        }

        $model = $this->vehicleModelCatalog->firstWhere('id', (int) $value);
        $brandName = $this->vehicleBrandCatalog->firstWhere('id', (int) $this->selected_brand_id)?->name;

        if ($model) {
            $this->vehicle_type = trim(($brandName ? $brandName . ' ' : '') . $model->name);
            $this->vehicle_price = floatval($model->price);
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

        if ($this->tour_id && $this->tour) {
            $schedule = null;
            $scheduleAccommodation = null;
        } else {
            $schedule = Schedule::query()
                ->forRouteAndDate($this->origin, $this->destination, $this->departure_date, $this->mode)
                ->findOrFail($this->selected_schedule_id);

            $scheduleAccommodation = $this->selected_schedule_accommodation_id
                ? ScheduleAccommodation::find($this->selected_schedule_accommodation_id)
                : null;
        }

        $transaction = null;

        DB::transaction(function () use (&$transaction, $schedule, $scheduleAccommodation) {
            $booking = Booking::create([
                'transaction_number' => $this->generateTransactionNumber(),
                'origin' => $this->origin,
                'destination' => $this->destination,
                'departure_date' => $this->departure_date,
                'return_date' => $this->return_date,
                'schedule_id' => $schedule?->id,
                'schedule_service' => $schedule?->service_name,
                'schedule_departure_time' => $schedule?->formatted_departure,
                'schedule_arrival_time' => $schedule?->formatted_arrival,
                'schedule_price' => $schedule?->price,
                'schedule_accommodation_id' => $scheduleAccommodation?->id,
                'schedule_accommodation_name' => $scheduleAccommodation?->name,
                'schedule_accommodation_price' => $scheduleAccommodation?->price,
                'tour_id' => $this->tour_id,
                'tour_date_id' => $this->tour_date_id,
                'tour_inclusions' => $this->tour?->inclusions,
                'client_name' => $this->client_name,
                'client_email' => $this->client_email,
                'total_price' => $this->calculateTotalPrice(),
                'status' => 'pending',
                'has_vehicle' => $this->has_vehicle,
                'vehicle_type' => $this->vehicle_type,
                'vehicle_plate_number' => $this->vehicle_plate_number,
                'vehicle_price' => $this->vehicle_price,
                'driver_name' => $this->driver_name,
                'driver_birthday' => $this->driver_birthday,
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
                $transportClass = TransportClass::query()->find($this->selected_transport_class_id);
                if ($transportClass) {
                    $booking->transportClasses()->attach($transportClass->id, [
                        'price' => $transportClass->price,
                    ]);
                }
            }

            if ($this->selected_hotel_id) {
                $hotel = Accommodation::query()->find($this->selected_hotel_id);
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

            Mail::to($booking->client_email)->send(new BookingCreated($booking, $receiptPath));
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
            'operator' => $this->operator,
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
            'vehicle_booking_method' => $this->vehicle_booking_method,
            'selected_vehicle_rate_id' => $this->selected_vehicle_rate_id,
            'selected_brand_id' => $this->selected_brand_id,
            'selected_model_id' => $this->selected_model_id,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_plate_number' => $this->vehicle_plate_number,
            'vehicle_price' => $this->vehicle_price,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'selected_hotel_id' => $this->selected_hotel_id,
            'tour_id' => $this->tour_id,
            'tour_date_id' => $this->tour_date_id,
        ]]);
    }

    protected function stepRules(): array
    {
        return match ($this->step) {
            1 => [
                'trip_type' => 'required|string|in:one_way,round_trip',
                'mode' => $this->tour_id ? 'nullable' : 'required|string|in:ferry,airline',
                'origin' => $this->tour_id ? 'nullable' : 'required|string|max:255',
                'destination' => $this->tour_id ? 'nullable' : 'required|string|max:255',
                'departure_date' => 'required|date',
                'tour_date_id' => $this->tour_id ? 'required|integer|exists:tour_dates,id' : 'nullable',
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
            'vehicle_booking_method' => 'required|string|in:category,brand_model',
            'selected_vehicle_rate_id' => $this->has_vehicle && $this->vehicle_booking_method === 'category' && $this->vehicleRateCatalog->isNotEmpty() ? 'required|integer|exists:vehicle_rates,id' : 'nullable',
            'selected_brand_id' => $this->has_vehicle && $this->vehicle_booking_method === 'brand_model' ? 'required|integer|exists:vehicle_brands,id' : 'nullable',
            'selected_model_id' => $this->has_vehicle && $this->vehicle_booking_method === 'brand_model' ? 'required|integer|exists:vehicle_models,id' : 'nullable',
            'vehicle_type' => $this->vehicleRateCatalog->isNotEmpty() ? 'nullable|string|max:255' : 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_plate_number' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'vehicle_price' => 'required_if:has_vehicle,true|nullable|numeric|min:0',
            ],
            2 => [
                'selected_schedule_id' => $this->tour_id ? 'nullable' : 'required|integer|exists:schedules,id',
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
            'mode' => $this->tour_id ? 'nullable' : 'required|string|in:ferry,airline',
            'origin' => $this->tour_id ? 'nullable' : 'required|string|max:255',
            'destination' => $this->tour_id ? 'nullable' : 'required|string|max:255',
            'departure_date' => 'required|date',
            'tour_date_id' => $this->tour_id ? 'required|integer|exists:tour_dates,id' : 'nullable',
            'return_date' => $this->trip_type === 'round_trip' ? 'required|date|after_or_equal:departure_date' : 'nullable|date|after_or_equal:departure_date',
            'selected_schedule_id' => $this->tour_id ? 'nullable' : 'required|integer|exists:schedules,id',
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
            'vehicle_booking_method' => 'required|string|in:category,brand_model',
            'driver_name' => 'required_if:has_vehicle,true|nullable|string|max:255',
            'driver_birthday' => 'required_if:has_vehicle,true|nullable|date',
            'selected_vehicle_rate_id' => $this->vehicle_booking_method === 'category' && $this->vehicleRateCatalog->isNotEmpty() ? 'required_if:has_vehicle,true|nullable|integer|exists:vehicle_rates,id' : 'nullable',
            'selected_brand_id' => $this->vehicle_booking_method === 'brand_model' ? 'required_if:has_vehicle,true|nullable|integer|exists:vehicle_brands,id' : 'nullable',
            'selected_model_id' => $this->vehicle_booking_method === 'brand_model' ? 'required_if:has_vehicle,true|nullable|integer|exists:vehicle_models,id' : 'nullable',
            'vehicle_type' => $this->vehicleRateCatalog->isNotEmpty() ? 'nullable|string|max:255' : 'required_if:has_vehicle,true|nullable|string|max:255',
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
        // If booking a prefilled package from CSV API or a tour with package_price
        if (($this->prefilled_from_package || $this->tour_id) && !empty($this->package_price)) {
            // Parse package_price: remove currency symbols and commas
            $cleanPrice = preg_replace('/[^0-9.]/', '', $this->package_price);
            $base = floatval($cleanPrice);
            $transportTotal = $base * count($this->passengers);
            $vehicleTotal = $this->has_vehicle ? floatval($this->vehicle_price ?? 0) : 0;
            $hotelTotal = $this->selected_hotel_id
                ? floatval($this->accommodationCatalog->firstWhere('id', $this->selected_hotel_id)->price ?? 0)
                : 0;

            $settings = PaymentSetting::current();
            $serviceFee = (count($this->passengers) * floatval($settings->fee_per_person));

            return $transportTotal + $vehicleTotal + $hotelTotal + $serviceFee;
        }
        
        // If booking an Eloquent tour with tour pricing (future use when price_from is added)
        if ($this->tour_id && $this->tour) {
            $base = property_exists($this->tour, 'price_from') ? $this->tour->price_from : 0;

            if ($this->tour_date_id) {
                $date = $this->selectedTourDate ?? TourDate::find($this->tour_date_id);
                if ($date && property_exists($date, 'price') && $date->price) {
                    $base = $date->price;
                }
            }

            $transportTotal = floatval($base) * count($this->passengers);
            $vehicleTotal = $this->has_vehicle ? floatval($this->vehicle_price ?? 0) : 0;
            $hotelTotal = $this->selected_hotel_id
                ? floatval($this->accommodationCatalog->firstWhere('id', $this->selected_hotel_id)->price ?? 0)
                : 0;

            $settings = PaymentSetting::current();
            $serviceFee = (count($this->passengers) * floatval($settings->fee_per_person));

            return $transportTotal + $vehicleTotal + $hotelTotal + $serviceFee;
        }

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
