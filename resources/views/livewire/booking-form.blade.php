<div class="min-h-screen bg-emerald-50">
    <div class="min-h-screen w-full bg-emerald-50 overflow-visible">
            <div class="bg-emerald-900 px-6 py-8 sm:px-10">
                <h1 class="text-3xl font-semibold text-white">Amiga Gracia Travel Booking</h1>
                <p class="mt-2 text-emerald-200 max-w-2xl">Complete your travel booking in a few easy steps. Your confirmation email and payment QR code are created automatically when you submit.</p>
            </div>

            <div class="p-6 sm:p-10">
                <div class="mb-8">
                    @php
                        $steps = ['Route','Schedule','Discount','Stay','Submit'];
                        $progressClass = match ($step) {
                            1 => 'w-0',
                            2 => 'w-1/4',
                            3 => 'w-1/2',
                            4 => 'w-3/4',
                            5 => 'w-full',
                            default => 'w-0',
                        };
                    @endphp
                    <div class="relative px-2 py-6">
                        <div class="absolute inset-x-0 top-1/2 h-0.5 bg-emerald-200"></div>
                        <div class="absolute left-0 top-1/2 h-0.5 bg-emerald-600 transition-all {{ $progressClass }}"></div>
                        <div class="relative z-10 flex w-full items-center justify-between gap-0">
                            @foreach($steps as $index => $label)
                                <div class="flex min-w-[4.5rem] flex-col items-center justify-center text-center">
                                    <div class="relative z-10 flex h-12 w-12 items-center justify-center rounded-full border-2 transition {{ $step === $index + 1 ? 'border-emerald-600 bg-emerald-600 text-white shadow-lg' : 'border-emerald-300 bg-white text-emerald-900' }}">
                                        <span class="font-semibold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="mt-3 text-[10px] font-semibold uppercase tracking-wide {{ $step === $index + 1 ? 'text-emerald-700' : 'text-emerald-500' }}">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submit" class="space-y-8 booking-form">
                    <style>
                        form.booking-form input,
                        form.booking-form select,
                        form.booking-form textarea {
                            border-color: #34d399;
                            accent-color: #10b981;
                        }
                        form.booking-form input:focus,
                        form.booking-form select:focus,
                        form.booking-form textarea:focus {
                            outline: none;
                            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
                            border-color: #047857;
                        }
                        form.booking-form input[type=date],
                        form.booking-form select {
                            background: #f0fdf4;
                            color: #064e3b;
                        }
                        form.booking-form input[type=date]::-webkit-calendar-picker-indicator {
                            filter: invert(31%) sepia(67%) saturate(464%) hue-rotate(120deg) brightness(94%) contrast(89%);
                        }
                        form.booking-form select option,
                        form.booking-form select optgroup {
                            background: #ecfdf5;
                            color: #065f46;
                        }
                        form.booking-form select option:hover,
                        form.booking-form select option:focus,
                        form.booking-form select option:checked {
                            background: #10b981 !important;
                            color: #ffffff !important;
                        }
                        form.booking-form select::-ms-expand {
                            display: none;
                        }

                        .hide-scrollbar {
                            -ms-overflow-style: none;
                            scrollbar-width: none;
                        }
                        .hide-scrollbar::-webkit-scrollbar {
                            display: none;
                            width: 0;
                            height: 0;
                        }
                    </style>
                    @if ($step === 1)
                        <div class="space-y-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="button" wire:click="setTripType('one_way')" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ $trip_type === 'one_way' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' }}">
                                    One-way Trip
                                </button>
                                <button type="button" wire:click="setTripType('round_trip')" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ $trip_type === 'round_trip' ? 'bg-emerald-900 text-white' : 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' }}">
                                    Round Trip
                                </button>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="relative block">
                                    <span class="text-emerald-700 font-medium">Mode</span>
                                    <button type="button" wire:click.prevent="toggleModeDropdown" class="mt-2 flex h-12 w-full items-center justify-between rounded-3xl border border-emerald-300 bg-white px-4 py-3 text-left text-emerald-900 shadow-sm transition hover:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                        <span>{{ $mode ? ucfirst($mode) : 'Select mode' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    @error('mode')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                    @if ($showModeDropdown)
                                        <div class="absolute left-0 right-0 top-full -mt-px z-20 rounded-b-3xl border border-slate-200 bg-white shadow-2xl">
                                            <div class="max-h-64 overflow-y-auto border-t border-slate-200 px-4 py-3 space-y-2">
                                                @php
                                                    $modeOptions = collect($this->getModeOptions());
                                                @endphp

                                                @foreach($modeOptions as $key => $label)
                                                    <button type="button" wire:click.prevent="selectMode('{{ $key }}')" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-emerald-900 hover:bg-emerald-50">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <span>{{ $label }}</span>
                                                            @if($mode === $key)
                                                                <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                                            @endif
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </label>

                            <div class="lg:col-span-2">
                            <div class="grid gap-6 lg:grid-cols-2">
                            <label class="relative block">
                                <span class="text-emerald-700 font-medium">Origin</span>
                                <button type="button" wire:click.prevent="toggleOriginDropdown" @if($mode === '') disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-3xl border border-emerald-300 bg-white px-4 py-3 text-left text-emerald-900 shadow-sm transition hover:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 disabled:cursor-not-allowed disabled:bg-emerald-100">
                                    <span>{{ $origin ?: ($mode === '' ? 'Select mode first' : 'Select origin') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @error('origin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                @if ($showOriginDropdown && $mode !== '')
                                    <div class="absolute left-0 right-0 top-full -mt-px z-20 max-h-96 overflow-hidden rounded-b-3xl border border-slate-200 bg-white shadow-2xl">
                                        <div class="p-4">
                                            <input type="text" wire:model.debounce.150ms="originSearch" placeholder="Search origins" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                                        </div>
                                        <div class="max-h-[14rem] overflow-y-auto hide-scrollbar border-t border-slate-200 px-4 py-3 space-y-2">
                                            @forelse($this->filteredOrigins as $originOption)
                                                <button type="button" wire:click.prevent="selectOrigin('{{ $originOption }}')" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-emerald-900 hover:bg-emerald-50">
                                                    {{ $originOption }}
                                                </button>
                                            @empty
                                                <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                                                    No origins match your search.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif
                            </label>

                            <label class="relative block">
                                <span class="text-emerald-700 font-medium">Destination</span>
                                <button type="button" wire:click.prevent="toggleDestinationDropdown" @if($mode === '' || $origin === '') disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-3xl border border-emerald-300 bg-white px-4 py-3 text-left text-emerald-900 shadow-sm transition hover:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 disabled:cursor-not-allowed disabled:bg-emerald-100">
                                    <span>{{ $destination ?: (blank($origin) ? 'Select origin first' : 'Select destination') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @error('destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                @if ($showDestinationDropdown && filled($origin))
                                    <div class="absolute left-0 right-0 top-full -mt-px z-20 max-h-96 overflow-hidden rounded-b-3xl border border-slate-200 bg-white shadow-2xl">
                                        <div class="p-4">
                                            <input type="text" wire:model.debounce.150ms="destinationSearch" placeholder="Search destinations" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                                        </div>
                                        <div class="max-h-[14rem] overflow-y-auto hide-scrollbar border-t border-slate-200 px-4 py-3 space-y-2">
                                            @forelse($this->filteredDestinations as $destinationOption)
                                                <button type="button" wire:click.prevent="selectDestination('{{ $destinationOption }}')" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-emerald-900 hover:bg-emerald-50">
                                                    {{ $destinationOption }}
                                                </button>
                                            @empty
                                                <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                                                    No destinations match your search.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif
                            </label>
                            </div>
                            </div>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="block">
                                <livewire:date-picker field="departure_date" :value="$departure_date" label="Departure Date" :min="date('Y-m-d')" />
                                @error('departure_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            @if($trip_type === 'round_trip')
                                <div class="block">
                                    <livewire:date-picker field="return_date" :value="$return_date" label="Return Date" :min="$departure_date" />
                                    @error('return_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="grid gap-4 rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm lg:grid-cols-[1fr_auto] lg:items-center">
                                <div>
                                    <p class="text-emerald-700 font-semibold">Travelers</p>
                                    <p class="mt-2 text-sm text-slate-600">Limit 8 travelers total for adults and children combined.</p>
                                </div>
                                <button type="button" wire:click.prevent="togglePassengerInfoModal" class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-900 transition hover:border-emerald-300">
                                    Learn more
                                </button>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-2">
                                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-emerald-700 font-medium">Adults</p>
                                            <p class="mt-1 text-sm text-slate-600">Age 13 and up</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click.prevent="decrementAdults" @if($adults <= 1) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-300 bg-white text-emerald-900 transition hover:border-emerald-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">−</button>
                                            <span class="min-w-[3rem] text-center text-lg font-semibold text-emerald-900">{{ $adults }}</span>
                                            <button type="button" wire:click.prevent="incrementAdults" @if($adults + $children >= 8) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-300 bg-white text-emerald-900 transition hover:border-emerald-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">+</button>
                                        </div>
                                    </div>
                                    @error('adults')<p class="mt-3 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-emerald-700 font-medium">Children</p>
                                            <p class="mt-1 text-sm text-slate-600">Under 13 years old</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click.prevent="decrementChildren" @if($children <= 0) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-300 bg-white text-emerald-900 transition hover:border-emerald-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">−</button>
                                            <span class="min-w-[3rem] text-center text-lg font-semibold text-emerald-900">{{ $children }}</span>
                                            <button type="button" wire:click.prevent="incrementChildren" @if($adults + $children >= 8) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-300 bg-white text-emerald-900 transition hover:border-emerald-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">+</button>
                                        </div>
                                    </div>
                                    @error('children')<p class="mt-3 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-900">
                                Total travelers: <span class="font-semibold">{{ $adults + $children }}</span> / 8
                            </div>

                            @if ($showPassengerInfoModal)
                                <div class="fixed inset-0 z-50 flex items-start justify-center bg-slate-900/50 p-4 pt-24">
                                    <div class="relative w-full max-w-2xl overflow-hidden rounded-3xl bg-white p-6 shadow-2xl">
                                        <button type="button" wire:click.prevent="togglePassengerInfoModal" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100">
                                            <span aria-hidden="true">×</span>
                                            <span class="sr-only">Close</span>
                                        </button>

                                        <h2 class="text-xl font-semibold text-slate-900">Passenger limits and guidance</h2>
                                        <p class="mt-3 text-slate-600">You can book up to 8 travelers total. This includes both adults and children combined. Any discounts are applied per traveler on the next step.</p>
                                        <ul class="mt-4 space-y-3 text-slate-700">
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">1</span>
                                                <span>Adults are counted separately from children, but both count toward the same 8-person total.</span>
                                            </li>
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">2</span>
                                                <span>Children under 13 are still part of the booking capacity limit.</span>
                                            </li>
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">3</span>
                                                <span>Use the buttons to update counts. The form prevents totals above 8.</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($step === 2)
                        <div class="space-y-4">
                            <p class="text-emerald-700">Choose the schedule that works best for your trip.</p>
                            <div class="grid gap-4 lg:grid-cols-3">
                                @forelse($availableSchedules as $schedule)
                                    <button type="button" wire:click.prevent="selectSchedule({{ $schedule['id'] }})" class="rounded-3xl border p-6 text-left transition duration-200 {{ $selected_schedule_id === $schedule['id'] ? 'border-emerald-900 bg-emerald-900 text-white' : 'border-emerald-200 bg-white text-emerald-900 hover:border-emerald-900' }}">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $schedule['service'] }}</h3>
                                                <p class="mt-1 text-sm text-emerald-500">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                            </div>
                                            <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $selected_schedule_id === $schedule['id'] ? 'border-white bg-white/10 text-white' : 'border-emerald-200 text-emerald-600' }}">{{ $schedule['availability'] }}</span>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between">
                                            <p class="text-sm text-emerald-500">Duration: {{ $schedule['duration'] }}</p>
                                            <p class="text-xl font-semibold">₱{{ number_format($schedule['price'], 2) }}</p>
                                        </div>
                                    </button>
                                @empty
                                    <p class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-700 lg:col-span-3">No schedules are available for this route on the selected date. Go back and try another date, or contact Amiga Gracia Travel Services.</p>
                                @endforelse
                            </div>
                            @error('selected_schedule_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    @if ($step === 3)
                        <div class="space-y-4">
                            <p class="text-emerald-700">Each traveler can have their own discount, if eligible. Name is required, discount is optional.</p>

                            @php
                                $typeLabels = ['adult' => 'Adult', 'child' => 'Child'];
                                $countByType = [];
                                $availableDiscounts = $discounts->reject(function ($discount) {
                                    return str_contains(strtolower($discount->name), 'infant');
                                });
                            @endphp

                            @foreach($passengers as $index => $passenger)
                                @php
                                    $countByType[$passenger['type']] = ($countByType[$passenger['type']] ?? 0) + 1;
                                @endphp
                                <div wire:key="passenger-{{ $index }}" class="grid gap-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 lg:grid-cols-[auto_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)] lg:items-end">
                                    <div class="rounded-full bg-emerald-900 px-3 py-1.5 text-center text-sm font-semibold text-white lg:self-center lg:min-w-[72px] lg:max-w-[96px]">
                                        {{ $typeLabels[$passenger['type']] }} {{ $countByType[$passenger['type']] }}
                                    </div>

                                    <label class="block min-w-0">
                                        <span class="text-emerald-700 font-medium">Name</span>
                                        <div class="mt-2 grid gap-2 sm:grid-cols-3">
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.first_name" class="block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="First" />
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.middle_name" class="block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Middle" />
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.last_name" class="block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Last" />
                                        </div>
                                        @error('passengers.' . $index . '.first_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        @error('passengers.' . $index . '.last_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </label>

                                    <label class="block min-w-0">
                                        <span class="text-emerald-700 font-medium">Discount</span>
                                        <select wire:model.number="passengers.{{ $index }}.discount_id" wire:change="$refresh" class="mt-2 block w-full rounded-3xl border border-emerald-300 bg-white px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                            <option value="">No discount</option>
                                            @foreach($availableDiscounts as $discount)
                                                <option value="{{ $discount->id }}">{{ $discount->name }} ({{ $discount->percentage }}%)</option>
                                            @endforeach
                                        </select>
                                        @error('passengers.' . $index . '.discount_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </label>

                                    @php
                                        $selectedDiscount = $discounts->firstWhere('id', $passenger['discount_id']);
                                        $discountKey = strtolower($selectedDiscount->name ?? '');
                                    @endphp

                                    @if($selectedDiscount && str_contains($discountKey, 'student'))
                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">School name</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.student_school" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="School name" />
                                            @error('passengers.' . $index . '.student_school')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">Student number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.student_number" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Student number" />
                                            @error('passengers.' . $index . '.student_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @elseif($selectedDiscount && str_contains($discountKey, 'senior'))
                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">Date of birth</span>
                                            <input type="date" wire:model.defer="passengers.{{ $index }}.senior_dob" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                                            @error('passengers.' . $index . '.senior_dob')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">OSCA number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.senior_osca_number" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="OSCA number" />
                                            @error('passengers.' . $index . '.senior_osca_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @elseif($selectedDiscount && str_contains($discountKey, 'pwd'))
                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">Type of disability</span>
                                            <select wire:model="passengers.{{ $index }}.pwd_disability_type" class="mt-2 block w-full rounded-3xl border border-emerald-300 bg-white px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                                <option value="">Choose a disability type</option>
                                                <option value="deaf_or_hard_of_hearing">Deaf or Hard of Hearing</option>
                                                <option value="intellectual_disability">Intellectual Disability</option>
                                                <option value="learning_disability">Learning Disability</option>
                                                <option value="mental_disability">Mental Disability</option>
                                                <option value="physical_disability_orthopedic">Physical Disability (Orthopedic)</option>
                                                <option value="psychosocial_disability">Psychosocial Disability</option>
                                                <option value="speech_and_language_impairment">Speech and Language Impairment</option>
                                                <option value="visual_disability">Visual Disability</option>
                                                <option value="cancer_ra_11215">Cancer (R.A. 11215)</option>
                                                <option value="rare_disease_ra_10747">Rare Disease (R.A. 10747)</option>
                                                <option value="other">Others</option>
                                            </select>
                                            @error('passengers.' . $index . '.pwd_disability_type')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        @if(($passenger['pwd_disability_type'] ?? '') === 'other')
                                            <label class="block min-w-0">
                                                <span class="text-emerald-700 font-medium">Please specify</span>
                                                <input type="text" wire:model.defer="passengers.{{ $index }}.pwd_disability_other" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Type of disability" />
                                                @error('passengers.' . $index . '.pwd_disability_other')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                        @endif

                                        <label class="block min-w-0">
                                            <span class="text-emerald-700 font-medium">PWD ID number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.pwd_id_number" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="PWD ID number" />
                                            @error('passengers.' . $index . '.pwd_id_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($step === 4)
                        <div class="space-y-4">
                            <p class="text-emerald-700">Pick any accommodations you'd like to add to your trip (optional).</p>

                            @if($accommodationCatalog->isEmpty())
                                <p class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-700">No accommodations are available right now. You can continue without one.</p>
                            @else
                                <div class="grid gap-5 sm:grid-cols-2">
                                    @foreach($accommodationCatalog as $accommodation)
                                        @php $isSelected = isset($selected_accommodation_ids[$accommodation->id]); @endphp
                                        <button
                                            type="button"
                                            wire:key="accommodation-{{ $accommodation->id }}"
                                            wire:click.prevent="toggleAccommodation({{ $accommodation->id }})"
                                            class="text-left rounded-3xl border-2 overflow-hidden transition duration-200 {{ $isSelected ? 'border-emerald-900 shadow-md' : 'border-emerald-200 hover:border-emerald-400' }}"
                                        >
                                            <div class="relative h-40 w-full bg-emerald-200">
                                                @if($accommodation->cover_image)
                                                    <img src="{{ asset('storage/' . $accommodation->cover_image) }}" alt="{{ $accommodation->name }}" class="h-full w-full object-cover" />
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-emerald-600 text-sm">No photo</div>
                                                @endif
                                                @if($isSelected)
                                                    <span class="absolute top-3 right-3 rounded-full bg-emerald-900 text-white text-xs font-semibold px-3 py-1">Selected</span>
                                                @endif
                                            </div>
                                            <div class="p-4">
                                                <h3 class="font-semibold text-emerald-900">{{ $accommodation->name }}</h3>
                                                @if($accommodation->description)
                                                    <p class="mt-1 text-sm text-emerald-500 line-clamp-2">{{ $accommodation->description }}</p>
                                                @endif
                                                <p class="mt-2 text-lg font-semibold text-emerald-900">₱{{ number_format($accommodation->price, 2) }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($step === 5)
                        <div class="grid gap-6 lg:grid-cols-2">
                            <label class="block">
                                <span class="text-emerald-700 font-medium">Your name</span>
                                <input type="text" wire:model.defer="client_name" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Jane Doe" />
                                @error('client_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-emerald-700 font-medium">Email address</span>
                                <input type="email" wire:model.defer="client_email" class="mt-2 block w-full rounded-3xl border border-emerald-300 px-4 py-3 shadow-sm focus:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="you@example.com" />
                                @error('client_email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>

                        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                            <h2 class="text-lg font-semibold text-emerald-900">Review</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <p class="text-emerald-700"><span class="font-medium">Route:</span> {{ $origin }} → {{ $destination }}</p>
                                    <p class="text-emerald-700"><span class="font-medium">Dates:</span> {{ $departure_date }}{{ $return_date ? ' → ' . $return_date : '' }}</p>
                                    <p class="text-emerald-700"><span class="font-medium">Passengers:</span> {{ $adults }} adults, {{ $children }} children</p>
                                </div>

                                <div class="space-y-2">
                                    @php
                                        $selectedAccommodations = $accommodationCatalog->filter(fn ($a) => isset($selected_accommodation_ids[$a->id]));
                                        $discountedCount = collect($passengers)->filter(fn ($p) => !empty($p['discount_id']))->count();
                                    @endphp
                                    <p class="text-emerald-700"><span class="font-medium">Discounted travelers:</span> {{ $discountedCount }} of {{ count($passengers) }}</p>
                                    <p class="text-emerald-700"><span class="font-medium">Accommodations selected:</span> {{ $selectedAccommodations->count() }}</p>
                                    <p class="text-emerald-700"><span class="font-medium">Estimated total:</span> ₱{{ number_format($this->calculateTotalPrice(), 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                @forelse($passengers as $passenger)
                                    <div class="rounded-2xl bg-white p-4 border border-emerald-200 flex items-center justify-between">
                                        <span class="text-emerald-900">{{ ucfirst($passenger['type']) }}{{ $passenger['name'] ? ' — ' . $passenger['name'] : '' }}</span>
                                        <span class="text-emerald-600 text-sm">{{ optional($discounts->firstWhere('id', $passenger['discount_id']))->name ?? 'No discount' }}</span>
                                    </div>
                                @empty
                                    <p class="text-emerald-500">No passengers added yet.</p>
                                @endforelse
                            </div>

                            <div class="mt-6 space-y-3">
                                @forelse($selectedAccommodations as $accommodation)
                                    <div class="rounded-2xl bg-white p-4 border border-emerald-200">
                                        <p class="text-emerald-900 font-medium">{{ $accommodation->name }}</p>
                                        <p class="text-emerald-700">Price: ₱{{ number_format($accommodation->price, 2) }}</p>
                                    </div>
                                @empty
                                    <p class="text-emerald-500">No accommodations selected.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                            <h2 class="text-lg font-semibold text-emerald-900">Selected schedule</h2>
                            @php
                                $schedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                            @endphp
                            @if ($schedule)
                                <div class="mt-4 rounded-3xl bg-white p-4 border border-emerald-200">
                                    <p class="text-emerald-900 font-semibold">{{ $schedule['service'] }}</p>
                                    <p class="text-emerald-700">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                    <p class="text-emerald-700">Duration: {{ $schedule['duration'] }}</p>
                                    <p class="text-emerald-700">Price: ₱{{ number_format($schedule['price'], 2) }}</p>
                                </div>
                            @else
                                <p class="mt-4 text-emerald-700">No schedule selected yet.</p>
                            @endif
                        </div>

                        <div wire:ignore class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                            {!! app('captcha')->renderJs() !!}
                            {!! app('captcha')->display(['data-callback' => 'recaptchaCallback']) !!}
                        </div>
                        <input type="hidden" id="recaptchaTokenHidden" wire:model="recaptchaToken" name="recaptchaToken" />
                        @error('recaptchaToken')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                        <script>
                            function recaptchaCallback(token) {
                                var input = document.getElementById('recaptchaTokenHidden');
                                if (! input) {
                                    return;
                                }
                                input.value = token;
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        </script>
                        @endif

                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                            @if ($step > 1)
                                <button type="button" wire:click.prevent="previousStep" class="inline-flex items-center justify-center rounded-3xl border border-emerald-300 bg-white px-5 py-3 text-sm font-semibold text-emerald-900 shadow-sm transition hover:bg-emerald-50">Back</button>
                            @endif

                            @if ($step < 5)
                                <button type="button" wire:click.prevent="nextStep" class="inline-flex items-center justify-center rounded-3xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Next</button>
                            @else
                                <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">Submit Booking</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
