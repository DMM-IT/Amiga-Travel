<div class="min-h-screen bg-slate-50">
    <div class="min-h-screen w-full bg-slate-50 overflow-visible">
            {{-- Modern Gradient Header --}}
            <div class="relative bg-pink-600 px-4 sm:px-6 lg:px-10 py-8 sm:py-10 overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="absolute top-0 right-0 w-[400px] h-[400px] -translate-y-1/4 translate-x-1/4 text-white" viewBox="0 0 200 200" fill="currentColor">
                        <circle cx="100" cy="100" r="100" opacity="0.08"/>
                    </svg>
                    <svg class="absolute bottom-0 left-0 w-[300px] h-[300px] translate-y-1/4 -translate-x-1/4 text-white" viewBox="0 0 200 200" fill="currentColor">
                        <circle cx="100" cy="100" r="80" opacity="0.06"/>
                    </svg>
                </div>
                <div class="relative max-w-6xl mx-auto z-10">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Amiga Gracia Travel Booking</h1>
                    <p class="mt-3 text-pink-100 max-w-2xl text-base sm:text-lg">Complete your travel booking in a few easy steps. Your confirmation email and payment QR code are created automatically when you submit.</p>
                </div>
            </div>
            

            <div class="px-4 sm:px-6 lg:px-10 py-6 sm:py-10">
                <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    @php
                        $isTourPackage = $tour_id || $prefilled_from_package;
                        $steps = $isTourPackage ? ['Route','Discount','Stay','Submit'] : ['Route','Schedule','Discount','Stay','Submit'];
                        if ($isTourPackage && $step >=2) {
                            $adjustedStep = $step - 1;
                        } else {
                            $adjustedStep = $step;
                        }
                        $progressClass = $isTourPackage ? match ($adjustedStep) {
                            1 => 'w-0',
                            2 => 'w-1/3',
                            3 => 'w-2/3',
                            4 => 'w-full',
                            default => 'w-0',
                        } : match ($step) {
                            1 => 'w-0',
                            2 => 'w-1/4',
                            3 => 'w-1/2',
                            4 => 'w-3/4',
                            5 => 'w-full',
                            default => 'w-0',
                        };

                        $getIcon = function($label) {
                            return match($label) {
                                'Route' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" /></svg>',
                                'Schedule' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
                                'Discount' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>',
                                'Stay' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>',
                                'Submit' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                default => ''
                            };
                        };
                    @endphp
                    <div class="relative pt-4 pb-6">
                        <!-- track container spans from first circle center (2.25rem) to last circle center (2.25rem) -->
                        <div class="absolute left-[2.25rem] right-[2.25rem] top-[2.5rem] -translate-y-1/2 h-[4px] rounded-full bg-slate-200">
                            <!-- progress line -->
                            <div class="absolute top-0 left-0 bottom-0 rounded-full bg-[#216417] transition-all duration-500 {{ $progressClass }}"></div>
                        </div>
                        <div class="relative z-10 flex w-full items-start justify-between">
                            @foreach($steps as $index => $label)
                                <div class="flex min-w-[4.5rem] flex-col items-center justify-center text-center">
                                    <div class="relative z-10 flex h-12 w-12 items-center justify-center rounded-full border-2 transition-colors duration-500 {{ $step === $index + 1 ? 'border-[#216417] bg-[#216417] text-white shadow-lg shadow-black/10' : ($step > $index + 1 ? 'border-[#216417] bg-white text-[#216417]' : 'border-slate-300 bg-white text-slate-400') }}">
                                        @if($step > $index + 1)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            {!! $getIcon($label) !!}
                                        @endif
                                    </div>
                                    <div class="mt-3 text-[10px] font-bold uppercase tracking-wider {{ $step === $index + 1 ? 'text-slate-900' : ($step > $index + 1 ? 'text-[#216417]' : 'text-slate-400') }}">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if(!empty($package_name) || !empty($package_price))
                    <div class="mt-4 mb-8 rounded-2xl border border-slate-200 bg-white p-5 max-w-3xl mx-6 sm:mx-10 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Selected Package</div>
                                <div class="font-bold text-xl text-slate-900 mt-1">{{ $package_name }}</div>
                                @if(!empty($package_price))
                                    <div class="text-sm font-medium text-slate-500 mt-1">Starting from ₱{{ $package_price }}</div>
                                @endif
                            </div>
                            <div>
                                <a href="{{ url('/tour-package') }}" class="text-sm text-[#216417] font-semibold hover:text-[#216417]/80 hover:underline transition">Change package</a>
                            </div>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="space-y-8 booking-form">
                    <style>
                        form.booking-form input,
                        form.booking-form select,
                        form.booking-form textarea {
                            border-color: #e2e8f0; /* slate-200 */
                            accent-color: #216417;
                        }
                        form.booking-form input:focus,
                        form.booking-form select:focus,
                        form.booking-form textarea:focus {
                            outline: none;
                            box-shadow: 0 0 0 3px rgba(33, 100, 23, 0.15);
                            border-color: #216417;
                        }
                        form.booking-form input[type=date],
                        form.booking-form select {
                            background: #f8fafc; /* slate-50 */
                            color: #0f172a; /* slate-900 */
                        }
                        form.booking-form input[type=date]::-webkit-calendar-picker-indicator {
                            filter: invert(30%) sepia(50%) saturate(800%) hue-rotate(80deg) brightness(95%) contrast(90%); /* Matches #216417 roughly */
                        }
                        form.booking-form select option,
                        form.booking-form select optgroup {
                            background: #ffffff;
                            color: #0f172a;
                        }
                        form.booking-form select option:hover,
                        form.booking-form select option:focus,
                        form.booking-form select option:checked {
                            background: #216417 !important;
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
                            <div class="flex items-center gap-3">
                                <div class="relative inline-grid grid-cols-2 bg-slate-100 rounded-full p-1 border border-slate-200 shadow-inner w-full sm:w-auto">
                                    <div class="absolute top-1 bottom-1 w-[calc(50%-4px)] rounded-full bg-[#216417] shadow-sm transition-all duration-300 ease-in-out z-0 {{ $trip_type === 'round_trip' ? 'left-[calc(50%+2px)]' : 'left-1' }}"></div>
                                    
                                    <button type="button" wire:click="setTripType('one_way')" @disabled($prefilled_from_package || $tour_id) class="relative z-10 px-4 sm:px-8 py-2.5 text-sm font-bold rounded-full transition-colors duration-300 {{ $trip_type === 'one_way' ? 'text-white' : 'text-slate-500 hover:text-slate-900' }} {{ ($prefilled_from_package || $tour_id) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        One-way Trip
                                    </button>
                                    
                                    <button type="button" wire:click="setTripType('round_trip')" @disabled($prefilled_from_package || $tour_id) class="relative z-10 px-4 sm:px-8 py-2.5 text-sm font-bold rounded-full transition-colors duration-300 {{ $trip_type === 'round_trip' ? 'text-white' : 'text-slate-500 hover:text-slate-900' }} {{ ($prefilled_from_package || $tour_id) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        Round Trip
                                    </button>
                                </div>
                                
                                @if($prefilled_from_package || $tour_id)
                                    <span class="text-xs text-slate-500 font-medium">(Locked for tour packages)</span>
                                @endif
                            </div>

                            <div class="grid gap-6 lg:grid-cols-4 mt-4">
                                <label class="relative block">
                                    <span class="text-slate-700 font-semibold text-sm">Mode</span>
                                    <button type="button" wire:click.prevent="toggleModeDropdown" @if($prefilled_from_package) disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20 disabled:cursor-not-allowed disabled:bg-slate-50">
                                        <div class="flex items-center gap-2">
                                            @if($mode === 'ferry')
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
                                            @elseif($mode === 'airline')
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                            @endif
                                            <span>{{ $mode ? ucfirst($mode) : 'Select mode' }}</span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    @error('mode')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                    @if ($showModeDropdown)
                                        <div class="absolute left-0 right-0 top-full mt-1 z-20 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                            <div class="max-h-64 overflow-y-auto px-2 py-2 space-y-1">
                                                @php
                                                    $modeOptions = collect($this->getModeOptions());
                                                @endphp

                                                @foreach($modeOptions as $key => $label)
                                                    <button type="button" wire:click.prevent="selectMode('{{ $key }}')" class="w-full rounded-lg px-4 py-3 text-left text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 {{ $mode === $key ? 'bg-slate-50 font-semibold' : '' }}">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <div class="flex items-center gap-2">
                                                                @if($key === 'ferry')
                                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
                                                                @elseif($key === 'airline')
                                                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                                                @endif
                                                                <span>{{ $label }}</span>
                                                            </div>
                                                            @if($mode === $key)
                                                                <span class="rounded-full bg-[#db2777] px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">Selected</span>
                                                            @endif
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </label>

                                <label class="relative block">
                                    <span class="text-slate-700 font-semibold text-sm">Operator</span>
                                    <button type="button" wire:click.prevent="toggleOperatorDropdown" @if($prefilled_from_package || blank($mode)) disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $selectedOpLogo = null;
                                                if ($operator) {
                                                    if (stripos($operator, '2GO') !== false) $selectedOpLogo = '2GO-Logo.png';
                                                    elseif (stripos($operator, 'Starlite') !== false) $selectedOpLogo = 'starlite-Logo.jfif';
                                                    elseif (stripos($operator, 'Cebu') !== false) $selectedOpLogo = 'CebuPecific-Logo.png';
                                                    elseif (stripos($operator, 'Pal') !== false || stripos($operator, 'Philippine Airlines') !== false) $selectedOpLogo = 'Pal-Logo.jfif';
                                                    elseif (stripos($operator, 'AirAsia') !== false) $selectedOpLogo = 'AirAsia-Logo.png';
                                                }
                                            @endphp
                                            @if($selectedOpLogo)
                                                <div class="w-6 h-6 shrink-0 bg-white rounded flex items-center justify-center overflow-hidden">
                                                    <img src="{{ asset('images/' . $selectedOpLogo) }}" alt="{{ $operator }}" class="w-full h-full object-contain">
                                                </div>
                                            @endif
                                            <span>{{ $operator ?: 'All operators' }}</span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    @if ($showOperatorDropdown)
                                        <div class="absolute left-0 right-0 top-full mt-1 z-20 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                            <div class="max-h-64 overflow-y-auto px-2 py-2 space-y-1">
                                                <button type="button" wire:click.prevent="selectOperator(null)" class="w-full rounded-lg px-4 py-3 text-left text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 {{ blank($operator) ? 'bg-slate-50 font-semibold' : '' }}">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <span>All operators</span>
                                                        @if(blank($operator))
                                                            <span class="rounded-full bg-[#db2777] px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">Selected</span>
                                                        @endif
                                                    </div>
                                                </button>
                                                @foreach($this->operators as $op)
                                                    <button type="button" wire:click.prevent="selectOperator('{{ $op }}')" class="w-full rounded-lg px-4 py-3 text-left text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 {{ $operator === $op ? 'bg-slate-50 font-semibold' : '' }}">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <div class="flex items-center gap-2">
                                                                @php
                                                                    $opLogo = null;
                                                                    if (stripos($op, '2GO') !== false) $opLogo = '2GO-Logo.png';
                                                                    elseif (stripos($op, 'Starlite') !== false) $opLogo = 'starlite-Logo.jfif';
                                                                    elseif (stripos($op, 'Cebu') !== false) $opLogo = 'CebuPecific-Logo.png';
                                                                    elseif (stripos($op, 'Pal') !== false || stripos($op, 'Philippine Airlines') !== false) $opLogo = 'Pal-Logo.jfif';
                                                                    elseif (stripos($op, 'AirAsia') !== false) $opLogo = 'AirAsia-Logo.png';
                                                                @endphp
                                                                @if($opLogo)
                                                                    <div class="w-6 h-6 shrink-0 bg-white rounded flex items-center justify-center overflow-hidden">
                                                                        <img src="{{ asset('images/' . $opLogo) }}" alt="{{ $op }}" class="w-full h-full object-contain">
                                                                    </div>
                                                                @endif
                                                                <span>{{ $op }}</span>
                                                            </div>
                                                            @if($operator === $op)
                                                                <span class="rounded-full bg-[#db2777] px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">Selected</span>
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
                                <span class="text-slate-700 font-semibold text-sm">Origin</span>
                                <button type="button" wire:click.prevent="toggleOriginDropdown" @if($prefilled_from_package || $mode === '') disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500">
                                    <span>{{ $origin ?: ($mode === '' ? 'Select mode first' : 'Select origin') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @error('origin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                @if ($showOriginDropdown && $mode !== '')
                                    <div class="absolute left-0 right-0 top-full mt-1 z-20 max-h-96 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
                                        <div class="p-3 border-b border-slate-100">
                                            <input type="text" wire:model.debounce.150ms="originSearch" placeholder="Search origins" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" />
                                        </div>
                                        <div class="max-h-[14rem] overflow-y-auto hide-scrollbar px-2 py-2 space-y-1">
                                            @forelse($this->filteredOrigins as $originOption)
                                                <button type="button" wire:click.prevent="selectOrigin('{{ $originOption }}')" class="w-full rounded-lg px-4 py-2.5 text-left text-sm text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 {{ $origin === $originOption ? 'bg-slate-50 font-semibold' : '' }}">
                                                    {{ $originOption }}
                                                </button>
                                            @empty
                                                <div class="px-4 py-6 text-center text-sm text-slate-500">
                                                    No origins match your search.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif
                            </label>

                            <label class="relative block">
                                <span class="text-slate-700 font-semibold text-sm">Destination</span>
                                <button type="button" wire:click.prevent="toggleDestinationDropdown" @if($prefilled_from_package || $mode === '' || $origin === '') disabled @endif class="mt-2 flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500">
                                    <span>{{ $destination ?: (blank($origin) ? 'Select origin first' : 'Select destination') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @error('destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                @if ($showDestinationDropdown && filled($origin))
                                    <div class="absolute left-0 right-0 top-full mt-1 z-20 max-h-96 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
                                        <div class="p-3 border-b border-slate-100">
                                            <input type="text" wire:model.debounce.150ms="destinationSearch" placeholder="Search destinations" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" />
                                        </div>
                                        <div class="max-h-[14rem] overflow-y-auto hide-scrollbar px-2 py-2 space-y-1">
                                            @forelse($this->filteredDestinations as $destinationOption)
                                                <button type="button" wire:click.prevent="selectDestination('{{ $destinationOption }}')" class="w-full rounded-lg px-4 py-2.5 text-left text-sm text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 {{ $destination === $destinationOption ? 'bg-slate-50 font-semibold' : '' }}">
                                                    {{ $destinationOption }}
                                                </button>
                                            @empty
                                                <div class="px-4 py-6 text-center text-sm text-slate-500">
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
                                    <label class="block text-slate-700 font-semibold text-sm">Departure Date</label>
                                        <div class="mt-2">
                                            @php
                                                $enabledDepartureDates = !empty($available_package_dates) ? $available_package_dates : (!empty($available_schedule_dates) ? $available_schedule_dates : []);
                                            @endphp
                                            @if(!empty($enabledDepartureDates))
                                                <livewire:date-picker wire:model="departure_date" field="departure_date" :enabled-dates="$enabledDepartureDates" label="" min="{{ date('Y-m-d') }}" />
                                            @else
                                                <livewire:date-picker wire:model="departure_date" field="departure_date" label="" min="{{ date('Y-m-d') }}" />
                                            @endif
                                        </div>
                                    @error('departure_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                @if($trip_type === 'round_trip')
                                    <div class="block">
                                        <label class="block text-slate-700 font-semibold text-sm">Return Date</label>
                                        <div class="mt-2">
                                            @php
                                                $enabledReturnDates = !empty($available_package_dates) ? $available_package_dates : (!empty($available_schedule_dates) ? $available_schedule_dates : []);
                                            @endphp
                                            @if(!empty($enabledReturnDates))
                                                <livewire:date-picker wire:model="return_date" field="return_date" :enabled-dates="$enabledReturnDates" label="" min="{{ $departure_date ?? date('Y-m-d') }}" />
                                            @else
                                                <livewire:date-picker wire:model="return_date" field="return_date" label="" min="{{ $departure_date ?? date('Y-m-d') }}" />
                                            @endif
                                        </div>
                                        @error('return_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </div>
                                @endif
                        </div>

                        <div class="space-y-4">
                            <div class="grid gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:grid-cols-[1fr_auto] lg:items-center">
                                <div>
                                    <p class="text-slate-900 font-semibold">Travelers</p>
                                    <p class="mt-2 text-sm text-slate-600">Limit 8 travelers total for adults and children combined.</p>
                                </div>
                                <button type="button" wire:click.prevent="togglePassengerInfoModal" class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                    Learn more
                                </button>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-2">
                                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-slate-900 font-semibold">Adults</p>
                                            <p class="mt-1 text-sm text-slate-500">Age 13 and up</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click.prevent="decrementAdults" @if($adults <= 1) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-[#db2777] hover:text-[#db2777] disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">−</button>
                                            <span class="min-w-[3rem] text-center text-lg font-semibold text-slate-900">{{ $adults }}</span>
                                            <button type="button" wire:click.prevent="incrementAdults" @if($adults + $children >= 8) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-[#db2777] hover:text-[#db2777] disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">+</button>
                                        </div>
                                    </div>
                                    @error('adults')<p class="mt-3 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-slate-900 font-semibold">Children</p>
                                            <p class="mt-1 text-sm text-slate-500">Under 13 years old</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click.prevent="decrementChildren" @if($children <= 0) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-[#db2777] hover:text-[#db2777] disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">−</button>
                                            <span class="min-w-[3rem] text-center text-lg font-semibold text-slate-900">{{ $children }}</span>
                                            <button type="button" wire:click.prevent="incrementChildren" @if($adults + $children >= 8) disabled @endif class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-[#db2777] hover:text-[#db2777] disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400">+</button>
                                        </div>
                                    </div>
                                    @error('children')<p class="mt-3 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
                                Total travelers: <span class="font-bold text-slate-900">{{ $adults + $children }}</span> / 8
                            </div>

                            @if($mode === 'ferry')
                                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                        <div>
                                            <p class="text-slate-900 font-semibold">Vehicle booking</p>
                                            <p class="mt-1 text-sm text-slate-600">Add a vehicle to your ferry trip (optional).</p>
                                        </div>
                                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                                            <input type="checkbox" wire:model.live="has_vehicle" class="peer sr-only" />
                                            <span class="relative h-7 w-12 shrink-0 rounded-full bg-slate-200 transition peer-checked:bg-[#db2777] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#db2777]/30 after:absolute after:left-0.5 after:top-0.5 after:h-6 after:w-6 after:rounded-full after:bg-white after:shadow after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                                            <span class="text-sm font-semibold text-slate-700">{{ $has_vehicle ? 'Yes' : 'No' }}</span>
                                        </label>
                                    </div>

                                    @if ($has_vehicle)
                                        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-6">
                                            <div class="grid gap-4 {{ $vehicle_booking_method === 'brand_model' ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }} sm:grid-cols-2">
                                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <p class="text-sm font-semibold text-slate-900">Classify Cargo by:</p>
                                                    <div class="mt-4 space-y-2">
                                                        <label class="flex items-center gap-3 rounded-full border px-4 py-3 text-sm text-slate-900 transition {{ $vehicle_booking_method === 'category' ? 'border-[#db2777] bg-[#db2777]/5' : 'border-slate-200 bg-white hover:border-[#db2777]/50' }}">
                                                            <input type="radio" wire:model.live="vehicle_booking_method" value="category" class="h-4 w-4 text-[#db2777] border-slate-300 focus:ring-[#db2777]" />
                                                            <span class="font-medium">Category</span>
                                                        </label>

                                                        <label class="flex items-center gap-3 rounded-full border px-4 py-3 text-sm text-slate-900 transition {{ $vehicle_booking_method === 'brand_model' ? 'border-[#db2777] bg-[#db2777]/5' : 'border-slate-200 bg-white hover:border-[#db2777]/50' }}">
                                                            <input type="radio" wire:model.live="vehicle_booking_method" value="brand_model" class="h-4 w-4 text-[#db2777] border-slate-300 focus:ring-[#db2777]" />
                                                            <span class="font-medium">Brand</span>
                                                        </label>
                                                    </div>
                                                    @error('vehicle_booking_method')<p class="mt-3 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                </div>

                                                @if($vehicle_booking_method === 'category')
                                                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                        <label class="text-sm font-semibold text-slate-900">Category *</label>
                                                        <select wire:model.live="selected_vehicle_rate_id" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20">
                                                            <option value="">Select category</option>
                                                            @foreach($vehicleRateCatalog as $rate)
                                                                <option value="{{ $rate->id }}">{{ $rate->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('selected_vehicle_rate_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                    </div>
                                                @else
                                                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                        <label class="text-sm font-semibold text-slate-900">Brand *</label>
                                                        <select wire:model.live="selected_brand_id" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20">
                                                            <option value="">Select brand</option>
                                                            @foreach($vehicleBrandCatalog as $brand)
                                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('selected_brand_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                    </div>

                                                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                        <label class="text-sm font-semibold text-slate-900">Model *</label>
                                                        <select wire:model.live="selected_model_id" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" @if($vehicleModelCatalog->isEmpty()) disabled @endif>
                                                            <option value="">Select model</option>
                                                            @foreach($vehicleModelCatalog as $model)
                                                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('selected_model_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                    </div>
                                                @endif

                                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <label class="text-sm font-semibold text-slate-900">Plate Number *</label>
                                                    <input type="text" wire:model.defer="vehicle_plate_number" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" placeholder="e.g., ABC 1234" />
                                                    @error('vehicle_plate_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                </div>

                                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <p class="text-sm font-semibold text-slate-900">Cargo Rate</p>
                                                    <div class="mt-3 flex h-14 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 text-lg font-bold text-slate-900">
                                                        ₱{{ number_format($vehicle_price ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                                <label class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <span class="text-sm font-semibold text-slate-900">Driver name</span>
                                                    <input type="text" wire:model.defer="driver_name" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" placeholder="e.g., Juan Dela Cruz" />
                                                    @error('driver_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                </label>

                                                <label class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <span class="text-sm font-semibold text-slate-900">Driver birthday</span>
                                                    <input type="date" wire:model.defer="driver_birthday" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20" />
                                                    @error('driver_birthday')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($showPassengerInfoModal)
                                <div class="fixed inset-0 z-50 flex items-start justify-center bg-slate-900/50 p-4 pt-24 backdrop-blur-sm">
                                    <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white p-6 shadow-2xl">
                                        <button type="button" wire:click.prevent="togglePassengerInfoModal" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100">
                                            <span aria-hidden="true">×</span>
                                            <span class="sr-only">Close</span>
                                        </button>

                                        <h2 class="text-xl font-bold text-slate-900">Passenger limits and guidance</h2>
                                        <p class="mt-3 text-slate-600">You can book up to 8 travelers total. This includes both adults and children combined. Any discounts are applied per traveler on the next step.</p>
                                        <ul class="mt-4 space-y-3 text-slate-700">
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#db2777]/10 text-[#db2777] font-bold text-xs">1</span>
                                                <span>Adults are counted separately from children, but both count toward the same 8-person total.</span>
                                            </li>
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#db2777]/10 text-[#db2777] font-bold text-xs">2</span>
                                                <span>Children under 13 are still part of the booking capacity limit.</span>
                                            </li>
                                            <li class="flex gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#db2777]/10 text-[#db2777] font-bold text-xs">3</span>
                                                <span>Use the buttons to update counts. The form prevents totals above 8.</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($step === 2 && !$tour_id && !$prefilled_from_package)
                        <div class="space-y-4">
                            <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr] lg:items-start">
                                {{-- Left Column: Schedules and transport classes/accommodations --}}
                                <div class="space-y-6">
                                    <p class="text-slate-700 font-semibold">Choose the schedule that works best for your trip.</p>
                                    @if($this->baggageRules)
                                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                            <button type="button" wire:click.prevent="$toggle('showBaggageRules')" class="flex items-center justify-between w-full text-left">
                                                <div class="flex items-center gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#216417]" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M8 21h8v1a1 1 0 01-1 1H9a1 1 0 01-1-1v-1zm3-2h2v1h-2v-1zM4 7a3 3 0 013-3h10a3 3 0 013 3v8a3 3 0 01-3 3H7a3 3 0 01-3-3V7zm3-1a1 1 0 00-1 1v8a1 1 0 001 1h10a1 1 0 001-1V7a1 1 0 00-1-1H7z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="font-semibold text-slate-900">Baggage Rules</p>
                                                        <p class="text-sm text-slate-600">{{ \Illuminate\Support\Arr::get($this->baggageRules, 'name', 'View baggage allowances and fees') }}</p>
                                                    </div>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform {{ $showBaggageRules ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 011.06-1.06L12 14.44l3.97-3.97a.75.75 0 111.06 1.06l-4.5 4.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                            @if($showBaggageRules)
                                                <div class="mt-4 border-t border-slate-200 pt-4">
                                                    <p class="text-xs text-slate-500 mb-3">Last verified: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'meta.last_verified', 'July 23, 2026') }}</p>
                                                    @if(\Illuminate\Support\Arr::get($this->baggageRules, 'carry_on'))
                                                        <div class="mb-4">
                                                            <h4 class="font-semibold text-slate-900 mb-2">Carry-on Baggage</h4>
                                                            <p class="text-sm text-slate-600 mb-2">{{ \Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.description', '') }}</p>
                                                            <ul class="list-disc list-inside text-sm text-slate-600 space-y-1">
                                                                @if(\Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.combined_weight_kg'))
                                                                    <li>Combined weight: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.combined_weight_kg') }}kg</li>
                                                                @endif
                                                                @if(\Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.hand_carry_size_cm'))
                                                                    <li>Hand-carry size: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.hand_carry_size_cm') }}</li>
                                                                @endif
                                                                @if(\Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.personal_item_size_cm'))
                                                                    <li>Personal item size: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.personal_item_size_cm') }}</li>
                                                                @endif
                                                                @if(\Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.note'))
                                                                    <li>{{ \Illuminate\Support\Arr::get($this->baggageRules, 'carry_on.note') }}</li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage'))
                                                        <div>
                                                            <h4 class="font-semibold text-slate-900 mb-2">Checked Baggage</h4>
                                                            @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.free_allowance_kg'))
                                                                <p class="text-sm text-slate-600 mb-2">Free allowance: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.free_allowance_kg') }}kg</p>
                                                            @endif
                                                            @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.max_single_bag_weight_kg'))
                                                                <p class="text-sm text-slate-600 mb-2">Max single bag weight: {{ \Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.max_single_bag_weight_kg') }}kg</p>
                                                            @endif
                                                            @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.fare_bundles'))
                                                                <div class="overflow-x-auto">
                                                                    <table class="w-full text-sm text-slate-600">
                                                                        <thead class="text-slate-900 font-semibold">
                                                                            <tr>
                                                                                <th class="px-2 py-2 text-left">Fare Bundle</th>
                                                                                <th class="px-2 py-2 text-right">Free Checked</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.fare_bundles', []) as $bundle)
                                                                                <tr class="border-t border-slate-200">
                                                                                    <td class="px-2 py-2">{{ \Illuminate\Support\Arr::get($bundle, 'name') }}</td>
                                                                                    <td class="px-2 py-2 text-right">{{ \Illuminate\Support\Arr::get($bundle, 'free_checked_kg') }}kg</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                            @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.prepaid_online'))
                                                                <p class="text-sm text-slate-600 mt-2">Prepaid online (domestic 20kg): {{ \Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.prepaid_online.domestic_20kg_php', '') }}</p>
                                                            @endif
                                                            @if(\Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.excess_rate_php_per_kg'))
                                                                <p class="text-sm text-slate-600 mt-2">Excess rate: ₱{{ \Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.excess_rate_php_per_kg') }}/kg</p>
                                                            @endif
                                                            <p class="text-xs text-slate-500 mt-3">Rates subject to change - confirm at booking</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="grid gap-4 lg:grid-cols-2">
                                        @forelse($availableSchedules as $schedule)
                                            <button type="button" wire:click.prevent="selectSchedule({{ $schedule['id'] }})" class="rounded-2xl border p-6 text-left transition duration-200 {{ $selected_schedule_id === $schedule['id'] ? 'border-[#db2777] bg-[#db2777] text-white shadow-md' : 'border-slate-200 bg-white text-slate-900 hover:border-[#db2777]/50 hover:shadow-sm' }}">
                                                <div class="flex items-center justify-between gap-4">
                                                    <div class="flex items-start gap-4">
                                                        @php
                                                            $opName = $schedule['operator'] ?? '';
                                                            $opLogo = null;
                                                            if (stripos($opName, '2GO') !== false) $opLogo = '2GO-Logo.png';
                                                            elseif (stripos($opName, 'Starlite') !== false) $opLogo = 'starlite-Logo.jfif';
                                                            elseif (stripos($opName, 'Cebu') !== false) $opLogo = 'CebuPecific-Logo.png';
                                                            elseif (stripos($opName, 'Pal') !== false || stripos($opName, 'Philippine Airlines') !== false) $opLogo = 'Pal-Logo.jfif';
                                                            elseif (stripos($opName, 'AirAsia') !== false) $opLogo = 'AirAsia-Logo.png';
                                                        @endphp
                                                        @if($opLogo)
                                                            <div class="w-12 h-12 shrink-0 bg-white rounded border {{ $selected_schedule_id === $schedule['id'] ? 'border-white/30 shadow' : 'border-slate-200' }} flex items-center justify-center p-1 overflow-hidden mt-1">
                                                                <img src="{{ asset('images/' . $opLogo) }}" alt="{{ $opName }}" class="w-full h-full object-contain">
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h3 class="text-lg font-bold">{{ $schedule['service'] }}</h3>
                                                            @if ($schedule['operator'])
                                                                <p class="mt-1 text-sm font-medium {{ $selected_schedule_id === $schedule['id'] ? 'text-white/80' : 'text-slate-600' }}">
                                                                    {{ $schedule['operator'] }}
                                                                </p>
                                                            @endif
                                                            @if ($schedule['vehicle_name'])
                                                                <p class="mt-1 text-sm {{ $selected_schedule_id === $schedule['id'] ? 'text-white/80' : 'text-slate-600' }}">{{ $schedule['vehicle_name'] }}</p>
                                                            @endif
                                                            <p class="mt-2 text-sm font-semibold {{ $selected_schedule_id === $schedule['id'] ? 'text-white' : 'text-slate-900' }}">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $selected_schedule_id === $schedule['id'] ? 'border-white/30 bg-white/20 text-white' : 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $schedule['availability'] }}</span>
                                                </div>
                                                <div class="mt-5 pt-4 border-t {{ $selected_schedule_id === $schedule['id'] ? 'border-white/20' : 'border-slate-100' }} space-y-1">
                                                    <p class="text-sm font-medium {{ $selected_schedule_id === $schedule['id'] ? 'text-white/90' : 'text-slate-600' }}">Duration: {{ $schedule['duration'] }}</p>
                                                    <p class="text-lg font-bold {{ $selected_schedule_id === $schedule['id'] ? 'text-white' : 'text-slate-900' }}">Fare: ₱{{ number_format($schedule['price'], 2) }}</p>
                                                </div>
                                            </button>
                                        @empty
                                            <p class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-600 text-sm lg:col-span-2">No schedules are available for this route on the selected date. Go back and try another date, or contact Amiga Gracia Travel Services.</p>
                                        @endforelse
                                    </div>
                                    @error('selected_schedule_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror

                                    @if($selected_schedule_id)
                                        @php
                                            $selectedSchedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                                        @endphp

                                        {{-- Ferry: Show accommodations --}}
                                        @if($mode === 'ferry' && $selectedSchedule && !empty($selectedSchedule['accommodations']))
                                            <div class="mt-6 border-t border-slate-200 pt-6">
                                                <p class="text-slate-900 font-bold mb-4 text-lg">Select your accommodation:</p>
                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    @foreach($selectedSchedule['accommodations'] as $accommodation)
                                                        @php $isAccommodationSelected = $selected_schedule_accommodation_id === $accommodation['id']; @endphp
                                                        <button type="button" wire:click.prevent="selectScheduleAccommodation({{ $accommodation['id'] }})" class="rounded-2xl border-2 p-5 text-left transition duration-200 {{ $isAccommodationSelected ? 'border-[#db2777] bg-[#db2777]/5 shadow-sm' : 'border-slate-200 bg-white hover:border-[#db2777]/50 hover:shadow-sm' }}">
                                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                                <h4 class="font-bold text-slate-900">{{ $accommodation['name'] }}</h4>
                                                                @if($accommodation['has_bed'])
                                                                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full border border-slate-200">With Bed</span>
                                                                @endif
                                                            </div>
                                                            @if($accommodation['description'])
                                                                <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $accommodation['description'] }}</p>
                                                            @endif
                                                            <p class="mt-4 text-xl font-extrabold text-[#db2777]">₱{{ number_format($accommodation['price'], 2) }}</p>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Airline: Show transport classes --}}
                                        @if($mode === 'airline' && $selectedSchedule && !empty($selectedSchedule['transport_classes']))
                                            <div class="mt-6 border-t border-slate-200 pt-6">
                                                <p class="text-slate-900 font-bold mb-4 text-lg">Select your travel class:</p>
                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    @foreach($selectedSchedule['transport_classes'] as $class)
                                                        @php $isClassSelected = $selected_transport_class_id === $class['id']; @endphp
                                                        <button type="button" wire:click.prevent="selectTransportClass({{ $class['id'] }})" class="rounded-2xl border-2 p-5 text-left transition duration-200 overflow-hidden {{ $isClassSelected ? 'border-[#db2777] bg-[#db2777]/5 shadow-sm' : 'border-slate-200 bg-white hover:border-[#db2777]/50 hover:shadow-sm' }}">
                                                            <div class="relative h-32 w-full overflow-hidden rounded-xl bg-slate-100 mb-4 border border-slate-200">
                                                                @if($class['cover_image'])
                                                                    <img src="{{ $class['cover_image'] }}" alt="{{ $class['name'] }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105" />
                                                                @else
                                                                    <div class="flex h-full w-full items-center justify-center text-slate-400 text-sm font-medium">No photo</div>
                                                                @endif
                                                            </div>
                                                            <h4 class="font-bold text-slate-900 text-lg">{{ $class['name'] }}</h4>
                                                            @if($class['description'])
                                                                <p class="mt-1 text-sm text-slate-600 leading-relaxed">{{ $class['description'] }}</p>
                                                            @endif
                                                            <div class="mt-3 flex flex-wrap gap-2">
                                                                @if(!empty($class['row_start']) && !empty($class['row_end']))
                                                                    <span class="inline-flex text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">Rows {{ $class['row_start'] }}-{{ $class['row_end'] }}</span>
                                                                @endif
                                                                @if(!empty($class['seat_capacity']))
                                                                    <span class="inline-flex text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">{{ number_format($class['seat_capacity']) }} seats</span>
                                                                @endif
                                                            </div>
                                                            <p class="mt-4 text-xl font-extrabold text-[#db2777]">₱{{ number_format($class['price'], 2) }}</p>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Baggage toggle --}}
                                        @if($this->baggageRules)
                                            @php
                                                $freeAllowance = \Illuminate\Support\Arr::get($this->baggageRules, 'checked_baggage.free_allowance_kg');
                                            @endphp
                                            @if($freeAllowance)
                                                <div class="mt-6 border-t border-slate-200 pt-6">
                                                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                                                        <div>
                                                            <p class="text-slate-900 font-bold text-lg">Baggage Allowance</p>
                                                            <p class="mt-1 text-sm text-slate-600">Free baggage allowance: {{ $freeAllowance }}kg</p>
                                                        </div>
                                                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                                                            <input type="checkbox" wire:model.live="hasExtraBaggage" class="peer sr-only">
                                                            <span class="relative h-7 w-12 shrink-0 rounded-full bg-slate-200 transition peer-checked:bg-[#db2777] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#db2777]/30 after:absolute after:left-0.5 after:top-0.5 after:h-6 after:w-6 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-5"></span>
                                                            <span class="text-sm font-semibold text-slate-700">{{ $hasExtraBaggage ? 'Add Extra Baggage' : 'No Extra Baggage' }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Promo Ticket toggle --}}
                                        @php
                                            $activePromo = $this->getActivePromoTicket();
                                        @endphp
                                        @if($activePromo)
                                            <div class="mt-6 border-t border-slate-200 pt-6">
                                                <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border-2 border-[#db2777] bg-[#db2777]/5 p-5 shadow-sm">
                                                    <div>
                                                        <p class="text-slate-900 font-bold text-lg">Promotional Ticket Available!</p>
                                                        <p class="mt-1 text-sm text-slate-600">
                                                            Promo price: <span class="font-bold text-[#db2777]">₱{{ number_format($activePromo->promo_price, 2) }}</span>
                                                            &nbsp;|&nbsp; Remaining: {{ $activePromo->remaining_quantity }} of {{ $activePromo->quantity_available }}
                                                        </p>
                                                    </div>
                                                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                                                        <input type="checkbox" wire:model.live="use_promo_ticket" class="peer sr-only">
                                                        <span class="relative h-7 w-12 shrink-0 rounded-full bg-slate-200 transition peer-checked:bg-[#db2777] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#db2777]/30 after:absolute after:left-0.5 after:top-0.5 after:h-6 after:w-6 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-5"></span>
                                                        <span class="text-sm font-semibold text-slate-700">{{ $use_promo_ticket ? 'Use Promo Ticket' : 'Regular Ticket' }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                {{-- Right Column: Seat Map / Ferry summary --}}
                                @php
                                    $selectedSchedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                                    $selectedClass = $selectedSchedule && $selected_transport_class_id 
                                        ? collect($selectedSchedule['transport_classes'])->firstWhere('id', $selected_transport_class_id)
                                        : null;
                                @endphp
                                <div class="space-y-6">
                                    @if($mode === 'airline' && $selectedSchedule && $selectedClass)
                                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                            <div class="flex flex-wrap items-start justify-between gap-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-slate-900">Seat Assignment</h3>
                                                    <p class="mt-1 text-sm text-slate-600">Select a passenger, then choose an available seat in the selected cabin.</p>
                                                </div>
                                                @if(!empty($selectedSchedule['aircraft_capacity']))
                                                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">
                                                        Aircraft capacity: {{ number_format($selectedSchedule['aircraft_capacity']) }} seats
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-5 grid gap-3">
                                                @foreach($passengers as $index => $passenger)
                                                    @php
                                                        $isChoosingPassenger = $selectingSeatForPassengerIndex === $index;
                                                        $passengerSeat = $passenger['seat_number'] ?? null;
                                                    @endphp
                                                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border px-4 py-3 transition-colors {{ $isChoosingPassenger ? 'border-[#db2777] bg-[#db2777]/5 shadow-sm' : 'border-slate-200 bg-white' }}">
                                                        <div>
                                                            <p class="font-bold text-slate-900">{{ ucfirst($passenger['type']) }} {{ $index + 1 }}</p>
                                                            <p class="text-sm text-slate-600 font-medium">
                                                                {{ $passengerSeat ? 'Seat ' . $passengerSeat . ' • ' . ($passenger['seat_section'] ?? $selectedClass['name']) : 'No seat selected yet' }}
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <button type="button" wire:click.prevent="chooseSeatForPassenger({{ $index }})" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-[#db2777] hover:text-[#db2777] hover:bg-slate-50">
                                                                {{ $passengerSeat ? 'Change Seat' : 'Choose Seat' }}
                                                            </button>
                                                            @if($passengerSeat)
                                                                <button type="button" wire:click.prevent="clearSeatForPassenger({{ $index }})" class="rounded-full border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 transition hover:bg-rose-50">
                                                                    Clear
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        @if($selectingSeatForPassengerIndex !== null)
                                            <div class="rounded-2xl border border-[#db2777]/30 bg-[#db2777]/10 p-4 shadow-sm">
                                                <p class="text-[#db2777] font-bold">
                                                    Selecting seat for {{ ucfirst($passengers[$selectingSeatForPassengerIndex]['type']) }} {{ $selectingSeatForPassengerIndex + 1 }}
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                                            <div class="mb-6">
                                                <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $selectedClass['name'] }} Seats</h3>
                                                <p class="text-sm text-slate-600">{{ $selectedClass['description'] ?? '' }}</p>
                                                @if(!empty($selectedClass['row_start']) && !empty($selectedClass['row_end']))
                                                    <p class="mt-2 text-sm text-slate-500 font-medium">Cabin rows {{ $selectedClass['row_start'] }}-{{ $selectedClass['row_end'] }}</p>
                                                @endif
                                            </div>
                                            
                                            <div class="flex justify-center mb-6">
                                                <div class="flex items-center gap-2 bg-slate-100 rounded-full px-4 py-2 border border-slate-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                    </svg>
                                                    <span class="text-slate-700 font-bold text-sm">Front of Aircraft</span>
                                                </div>
                                            </div>
                                            
                                            <div class="overflow-x-auto pb-2" style="max-height: 500px; overflow-y: auto;">
                                                <div class="space-y-3">
                                                    @foreach($selectedClass['seat_rows'] as $row)
                                                        <div class="flex justify-center items-center gap-3">
                                                            <span class="text-sm font-bold text-slate-400 w-8 text-right">{{ $row['label'] }}</span>

                                                            <div class="flex items-center gap-2">
                                                                @foreach($row['left'] as $seatMeta)
                                                                    @php
                                                                        $seat = $seatMeta['id'];
                                                                        $isOccupied = in_array($seat, $selectedSchedule['occupied_seats']);
                                                                        $isSelectedForPassenger = collect($passengers)->contains(fn ($p) => $p['seat_number'] === $seat);
                                                                    @endphp
                                                                    <button type="button"
                                                                        @if(!$isOccupied && $selectingSeatForPassengerIndex !== null)
                                                                            wire:click.prevent="selectSeatForPassenger('{{ $seat }}')"
                                                                        @endif
                                                                        @disabled($isOccupied || $selectingSeatForPassengerIndex === null)
                                                                        class="w-14 h-14 rounded-lg border-2 flex items-center justify-center text-sm font-bold transition-all duration-200
                                                                            @if($isOccupied)
                                                                                bg-slate-100 text-slate-300 border-slate-200 cursor-not-allowed
                                                                            @elseif($isSelectedForPassenger)
                                                                                bg-[#db2777] text-white border-[#db2777] shadow-md scale-105
                                                                            @elseif($selectingSeatForPassengerIndex === null)
                                                                                bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed
                                                                            @else
                                                                                bg-white text-slate-700 border-slate-300 hover:border-[#db2777] hover:text-[#db2777] hover:bg-slate-50
                                                                            @endif
                                                                        "
                                                                    >
                                                                        {{ $seatMeta['label'] }}
                                                                    </button>
                                                                @endforeach
                                                            </div>

                                                            <div class="w-6"></div>

                                                            <div class="flex items-center gap-2">
                                                                @foreach($row['right'] as $seatMeta)
                                                                    @php
                                                                        $seat = $seatMeta['id'];
                                                                        $isOccupied = in_array($seat, $selectedSchedule['occupied_seats']);
                                                                        $isSelectedForPassenger = collect($passengers)->contains(fn ($p) => $p['seat_number'] === $seat);
                                                                    @endphp
                                                                    <button type="button"
                                                                        @if(!$isOccupied && $selectingSeatForPassengerIndex !== null)
                                                                            wire:click.prevent="selectSeatForPassenger('{{ $seat }}')"
                                                                        @endif
                                                                        @disabled($isOccupied || $selectingSeatForPassengerIndex === null)
                                                                        class="w-14 h-14 rounded-lg border-2 flex items-center justify-center text-sm font-bold transition-all duration-200
                                                                            @if($isOccupied)
                                                                                bg-slate-100 text-slate-300 border-slate-200 cursor-not-allowed
                                                                            @elseif($isSelectedForPassenger)
                                                                                bg-[#db2777] text-white border-[#db2777] shadow-md scale-105
                                                                            @elseif($selectingSeatForPassengerIndex === null)
                                                                                bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed
                                                                            @else
                                                                                bg-white text-slate-700 border-slate-300 hover:border-[#db2777] hover:text-[#db2777] hover:bg-slate-50
                                                                            @endif
                                                                        "
                                                                    >
                                                                        {{ $seatMeta['label'] }}
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="mt-8 pt-6 border-t border-slate-200">
                                                <div class="flex flex-wrap justify-center gap-8 text-sm">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-8 h-8 rounded-lg bg-white border-2 border-slate-300"></span>
                                                        <span class="text-slate-700 font-bold">Available</span>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-8 h-8 rounded-lg bg-[#db2777] border-2 border-[#db2777]"></span>
                                                        <span class="text-slate-900 font-bold">Selected</span>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-8 h-8 rounded-lg bg-slate-100 border-2 border-slate-200"></span>
                                                        <span class="text-slate-400 font-bold">Occupied</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($mode === 'airline' && $selectedSchedule && !$selectedClass)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                            </svg>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Select a Travel Class</h3>
                                            <p class="text-slate-600 font-medium">Choose your travel class from the options on the left to see available seats</p>
                                        </div>
                                    @elseif($mode === 'ferry' && $selectedSchedule)
                                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                            <div class="flex flex-wrap items-start justify-between gap-4">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Ferry booking details</h3>
                                                    <p class="mt-1 text-sm text-slate-600 font-medium">Review your selected ferry route and accommodation choice here.</p>
                                                </div>
                                                <span class="rounded-full bg-slate-100 border border-slate-200 px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-700">Ferry trip</span>
                                            </div>

                                            <div class="mt-6 space-y-4">
                                                <div class="rounded-xl border border-[#db2777]/20 bg-[#db2777]/5 p-4 shadow-sm">
                                                    <p class="text-slate-900 font-bold">Selected schedule</p>
                                                    <p class="mt-2 text-sm text-[#db2777] font-semibold">{{ $selectedSchedule['service'] }} · {{ $selectedSchedule['departure'] }} → {{ $selectedSchedule['arrival'] }}</p>
                                                    <p class="mt-1 text-sm text-slate-600">Duration: {{ $selectedSchedule['duration'] }}</p>
                                                    <p class="mt-1 text-sm text-slate-600 font-bold">Fare: ₱{{ number_format($selectedSchedule['price'], 2) }}</p>
                                                </div>

                                                @php $selectedAccommodation = collect($selectedSchedule['accommodations'] ?? [])->firstWhere('id', $selected_schedule_accommodation_id); @endphp

                                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <p class="text-slate-900 font-bold">Accommodation</p>
                                                    @if($selectedAccommodation)
                                                        <p class="mt-2 text-sm text-slate-700 font-semibold">{{ $selectedAccommodation['name'] }}</p>
                                                        <p class="mt-1 text-sm text-slate-600">Accommodation: ₱{{ number_format($selectedAccommodation['price'], 2) }}</p>
                                                        <p class="mt-1 text-sm text-slate-600">Ticket fare: ₱{{ number_format($selectedSchedule['price'], 2) }}</p>
                                                        <p class="mt-2 pt-2 border-t border-slate-100 text-sm text-slate-900 font-extrabold">Total per person: ₱{{ number_format($selectedSchedule['price'] + $selectedAccommodation['price'], 2) }}</p>
                                                        @if(!empty($selectedAccommodation['description']))
                                                            <p class="mt-3 text-sm text-slate-500 italic">{{ $selectedAccommodation['description'] }}</p>
                                                        @endif
                                                    @else
                                                        <p class="mt-2 text-sm text-slate-500 italic">Select an accommodation on the left to continue.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center shadow-sm">
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Pick a schedule to continue</h3>
                                            <p class="text-slate-600 font-medium">When you select a schedule, the next step will show the available class, seat, or accommodation options.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($step === 3)
                        <div class="space-y-4">
                            <p class="text-slate-700 font-semibold">Each traveler can have their own discount, if eligible. Name is required, discount is optional.</p>

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
                                <div wire:key="passenger-{{ $index }}" class="flex flex-col lg:flex-row gap-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                    <div class="flex-shrink-0 lg:w-32 lg:pt-8">
                                        <div class="rounded-full bg-[#db2777] px-4 py-2 text-center text-xs uppercase tracking-wider font-bold text-white shadow-sm inline-block w-full">
                                            {{ $typeLabels[$passenger['type']] }} {{ $countByType[$passenger['type']] }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex-grow grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:items-end">

                                    <label class="block min-w-0">
                                        <span class="text-slate-900 font-bold text-sm">Name</span>
                                        <div class="mt-3 grid gap-2 sm:grid-cols-3">
                                            <div>
                                                <input type="text" wire:model.defer="passengers.{{ $index }}.first_name" class="block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="First" />
                                                @error('passengers.' . $index . '.first_name')<p class="mt-2 text-xs text-rose-600">Required</p>@enderror
                                            </div>
                                            <div>
                                                <input type="text" wire:model.defer="passengers.{{ $index }}.middle_name" class="block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="Middle" />
                                            </div>
                                            <div>
                                                <input type="text" wire:model.defer="passengers.{{ $index }}.last_name" class="block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="Last" />
                                                @error('passengers.' . $index . '.last_name')<p class="mt-2 text-xs text-rose-600">Required</p>@enderror
                                            </div>
                                        </div>
                                    </label>

                                    <label class="block min-w-0">
                                        <span class="text-slate-900 font-bold text-sm">Discount</span>
                                        <select wire:model.number="passengers.{{ $index }}.discount_id" wire:change="$refresh" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all">
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
                                            <span class="text-slate-900 font-bold text-sm">Upload school ID</span>
                                            <input type="file" wire:model="studentIdProofs.{{ $index }}" accept="image/*" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" />
                                            @error('studentIdProofs.' . $index)<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        <label class="block min-w-0">
                                            <span class="text-slate-900 font-bold text-sm">Student number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.student_number" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="Student number" />
                                            @error('passengers.' . $index . '.student_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @elseif($selectedDiscount && str_contains($discountKey, 'senior'))
                                        <label class="block min-w-0">
                                            <span class="text-slate-900 font-bold text-sm">Date of birth</span>
                                            <input type="date" wire:model.defer="passengers.{{ $index }}.senior_dob" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" />
                                            @error('passengers.' . $index . '.senior_dob')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        <label class="block min-w-0">
                                            <span class="text-slate-900 font-bold text-sm">OSCA number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.senior_osca_number" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="OSCA number" />
                                            @error('passengers.' . $index . '.senior_osca_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @elseif($selectedDiscount && str_contains($discountKey, 'pwd'))
                                        <label class="block min-w-0">
                                            <span class="text-slate-900 font-bold text-sm">Type of disability</span>
                                            <select wire:model="passengers.{{ $index }}.pwd_disability_type" class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all">
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
                                                <span class="text-slate-900 font-bold text-sm">Please specify</span>
                                                <input type="text" wire:model.defer="passengers.{{ $index }}.pwd_disability_other" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="Type of disability" />
                                                @error('passengers.' . $index . '.pwd_disability_other')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                        @endif

                                        <label class="block min-w-0">
                                            <span class="text-slate-900 font-bold text-sm">PWD ID number</span>
                                            <input type="text" wire:model.defer="passengers.{{ $index }}.pwd_id_number" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="PWD ID number" />
                                            @error('passengers.' . $index . '.pwd_id_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>
                                    @endif

                                    @if ($mode === 'airline')
                                        <div class="space-y-3 pt-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-900 font-bold text-sm">Seat Number</span>
                                                @if(!empty($passenger['seat_number']))
                                                    <button type="button" wire:click.prevent="clearSeatForPassenger({{ $index }})" class="text-sm text-[#db2777] hover:text-[#db2777]/80 font-bold underline">Change Seat</button>
                                                @endif
                                            </div>
                                            <input type="text" wire:model="passengers.{{ $index }}.seat_number" class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm focus:outline-none text-slate-700" placeholder="Select seat from the seat map" readonly />

                                            <label class="block min-w-0">
                                                <span class="text-slate-900 font-bold text-sm">Seat Row</span>
                                                <input type="text" wire:model="passengers.{{ $index }}.seat_row" class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm focus:outline-none text-slate-700" placeholder="Auto-populated" readonly />
                                            </label>

                                            <label class="block min-w-0">
                                                <span class="text-slate-900 font-bold text-sm">Seat Section</span>
                                                <input type="text" wire:model="passengers.{{ $index }}.seat_section" class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm focus:outline-none text-slate-700" placeholder="Auto-populated" readonly />
                                            </label>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($step === 4)
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <p class="text-slate-700 font-semibold">Choose a place to stay in {{ $destination }} (optional).</p>
                                @php
                                    $currentDestination = $destination;
                                    $filteredHotels = $accommodationCatalog->filter(function ($acc) use ($currentDestination) {
                                        return $acc->destination === $currentDestination;
                                    });
                                @endphp

                                @if($filteredHotels->isEmpty())
                                    <p class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-700 font-medium text-sm">No accommodations are available in {{ $destination }} right now. You can continue without one.</p>
                                @else
                                    <div class="grid gap-5 sm:grid-cols-2">
                                        @foreach($filteredHotels as $hotel)
                                            @php $isSelected = $selected_hotel_id === $hotel->id; @endphp
                                            <button
                                                type="button"
                                                wire:key="hotel-{{ $hotel->id }}"
                                                wire:click.prevent="$set('selected_hotel_id', {{ $isSelected ? 'null' : $hotel->id }})"
                                                class="text-left rounded-2xl border-2 overflow-hidden transition duration-200 {{ $isSelected ? 'border-[#db2777] shadow-md ring-2 ring-[#db2777]/20' : 'border-slate-200 hover:border-[#db2777]/50 hover:shadow-sm' }}"
                                            >
                                                <div class="relative h-48 w-full bg-slate-100">
                                                    @if($hotel->cover_image)
                                                        <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105" />
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-slate-400 text-sm font-medium">No photo</div>
                                                    @endif
                                                    @if($isSelected)
                                                        <span class="absolute top-4 right-4 rounded-full bg-[#db2777] text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 shadow-sm">Selected</span>
                                                    @endif
                                                </div>
                                                <div class="p-5">
                                                    <h3 class="font-bold text-slate-900 text-lg">{{ $hotel->name }}</h3>
                                                    @if($hotel->description)
                                                        <p class="mt-2 text-sm text-slate-600 line-clamp-2 leading-relaxed">{{ $hotel->description }}</p>
                                                    @endif
                                                    <p class="mt-4 text-xl font-extrabold text-[#db2777]">₱{{ number_format($hotel->price, 2) }}</p>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($step === 5)
                        <div class="grid gap-6 lg:grid-cols-2">
                            <label class="block">
                                <span class="text-slate-900 font-bold text-sm">Your name</span>
                                <input type="text" wire:model.defer="client_name" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="Jane Doe" />
                                @error('client_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-900 font-bold text-sm">Email address</span>
                                <input type="email" wire:model.defer="client_email" class="mt-3 block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm focus:border-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#db2777]/20 transition-all" placeholder="you@example.com" />
                                @error('client_email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900">Review</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="space-y-3">
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Route:</span> {{ $origin }} → {{ $destination }}</p>
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Dates:</span> {{ $departure_date }}{{ $return_date ? ' → ' . $return_date : '' }}</p>
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Passengers:</span> {{ $adults }} adults, {{ $children }} children</p>
                                    @if ($selected_transport_class_id)
                                        @php $selectedClass = $transportClassCatalog->firstWhere('id', $selected_transport_class_id); @endphp
                                        <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Transport Class:</span> {{ $selectedClass->name }}</p>
                                    @endif
                                    @if ($has_vehicle)
                                        <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Vehicle:</span> {{ $vehicle_type }} ({{ $vehicle_plate_number }}) — ₱{{ number_format($vehicle_price ?? 0, 2) }}</p>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    @php
                                        $selectedSchedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                                        $selectedAccommodation = $selectedSchedule && $selected_schedule_accommodation_id
                                            ? collect($selectedSchedule['accommodations'])->firstWhere('id', $selected_schedule_accommodation_id)
                                            : null;
                                        $discountedCount = collect($passengers)->filter(fn ($p) => !empty($p['discount_id']))->count();
                                    @endphp
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Discounted travelers:</span> {{ $discountedCount }} of {{ count($passengers) }}</p>
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Accommodation selected:</span> {{ $selectedAccommodation ? $selectedAccommodation['name'] : 'None' }}</p>
                                    <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Estimated total:</span> <span class="font-extrabold text-[#db2777]">₱{{ number_format($this->calculateTotalPrice(), 2) }}</span></p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @forelse($passengers as $passenger)
                                    <div class="rounded-xl bg-white p-4 border border-slate-200 shadow-sm transition-shadow hover:shadow-md">
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-900 font-bold text-sm">{{ ucfirst($passenger['type']) }}{{ $passenger['name'] ? ' — ' . $passenger['name'] : '' }}</span>
                                            <span class="text-slate-500 text-xs font-semibold px-2 py-1 bg-slate-100 rounded-full">{{ optional($discounts->firstWhere('id', $passenger['discount_id']))->name ?? 'No discount' }}</span>
                                        </div>
                                        @if (isset($passenger['seat_number']) || isset($passenger['seat_section']))
                                            <div class="mt-3 text-sm text-slate-600 font-medium">
                                                Seat: <span class="text-[#db2777] font-bold">{{ isset($passenger['seat_number']) ? $passenger['seat_number'] . (isset($passenger['seat_row']) ? ' (Row ' . $passenger['seat_row'] . ')' : '') : 'Not selected' }}</span>
                                                @if (isset($passenger['seat_section']))
                                                    • {{ $passenger['seat_section'] }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-slate-500 italic">No passengers added yet.</p>
                                @endforelse
                            </div>

                            <div class="mt-6 space-y-3">
                                @if ($mode === 'ferry' && $has_vehicle)
                                    <div class="rounded-xl bg-white p-4 border border-slate-200 shadow-sm flex justify-between items-center">
                                        <div>
                                            <p class="text-slate-900 font-bold text-sm">Vehicle: <span class="text-[#db2777]">{{ $vehicle_type }}</span></p>
                                            <p class="text-slate-500 text-xs">Plate: {{ $vehicle_plate_number }}</p>
                                        </div>
                                        <p class="text-slate-900 font-bold">₱{{ number_format($vehicle_price ?? 0, 2) }}</p>
                                    </div>
                                @endif
                                @if ($selected_transport_class_id)
                                    <div class="rounded-xl bg-white p-4 border border-slate-200 shadow-sm flex justify-between items-center">
                                        <p class="text-slate-900 font-bold text-sm">Transport Class: <span class="text-[#db2777]">{{ $selectedClass->name }}</span></p>
                                        <p class="text-slate-900 font-bold">₱{{ number_format($selectedClass->price, 2) }}</p>
                                    </div>
                                @endif
                                @if ($selectedAccommodation)
                                    <div class="rounded-xl bg-white p-4 border border-slate-200 shadow-sm flex justify-between items-center">
                                        <p class="text-slate-900 font-bold text-sm">Accommodation: <span class="text-[#db2777]">{{ $selectedAccommodation['name'] }}</span></p>
                                        <p class="text-slate-900 font-bold">₱{{ number_format($selectedAccommodation['price'], 2) }}</p>
                                    </div>
                                @endif
                                @if ($selected_hotel_id)
                                    @php $selectedHotel = $accommodationCatalog->firstWhere('id', $selected_hotel_id); @endphp
                                    <div class="rounded-xl bg-white p-4 border border-slate-200 shadow-sm flex justify-between items-center">
                                        <p class="text-slate-900 font-bold text-sm">Hotel: <span class="text-[#db2777]">{{ $selectedHotel->name }}</span></p>
                                        <p class="text-slate-900 font-bold">₱{{ number_format($selectedHotel->price, 2) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900">Selected schedule</h2>
                            @php
                                $schedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                            @endphp
                            @if ($schedule)
                                <div class="mt-4 rounded-xl bg-white p-5 border border-slate-200 shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[#db2777] font-extrabold text-lg">{{ $schedule['service'] }}</p>
                                            <p class="text-slate-600 font-medium mt-1">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-slate-900 font-bold text-lg">₱{{ number_format($schedule['price'], 2) }}</p>
                                            <p class="text-slate-500 text-sm">Duration: {{ $schedule['duration'] }}</p>
                                        </div>
                                    </div>
                                    @if ($schedule['vehicle_name'])
                                        <div class="mt-3 pt-3 border-t border-slate-100">
                                            <p class="text-slate-700 text-sm"><span class="font-bold text-slate-900">Vehicle:</span> {{ $schedule['vehicle_name'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="mt-4 text-slate-500 italic">No schedule selected yet.</p>
                            @endif
                        </div>


                        @endif

                        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between mt-8 pt-6 border-t border-slate-200">
                            @if ($step > 1)
                                <button type="button" wire:click.prevent="previousStep" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-8 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Back
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if ($step < 5)
                                <button type="button" wire:click.prevent="nextStep" class="inline-flex items-center justify-center rounded-xl bg-[#db2777] px-8 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:bg-[#db2777]/90 hover:shadow-lg">
                                    Next
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#db2777] px-8 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:bg-[#db2777]/90 hover:shadow-lg ring-4 ring-[#db2777]/20">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Complete Booking
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Terms and Conditions Modal -->
    @if ($showTermsModal)
        <div x-data x-init="
            $nextTick(() => {
                const modal = $el;
                const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=\"-1\"])');
                const firstFocusable = focusableElements[0];
                const lastFocusable = focusableElements[focusableElements.length - 1];
                
                modal.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                    } else if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusable) {
                                e.preventDefault();
                                lastFocusable.focus();
                            }
                        } else {
                            if (document.activeElement === lastFocusable) {
                                e.preventDefault();
                                firstFocusable.focus();
                            }
                        }
                    }
                });
                
                firstFocusable.focus();
            });
        " class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900">Amiga Travel Agency Services Terms and Conditions</h2>
                </div>
                
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <p class="text-sm text-slate-700 mb-6">
                        Please go through these Terms and Conditions carefully. Your acceptance is required before continuing with your booking.
                    </p>
                    
                    <div class="space-y-6 text-sm text-slate-700">
                        <!-- Boarding Requirements -->
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-2">Boarding Requirements</h3>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>One printed copy of the eTicket Itinerary Receipt.</li>
                                <li>Presentation of each passenger's valid ID.</li>
                                <li>Passengers must arrive at the terminal 3–4 hours before departure. Boarding gates close 1 hour before departure.</li>
                                <li>The operating ferry carrier reserves the right to refuse boarding if a passenger cannot present the required documents upon request.</li>
                            </ul>
                        </div>
                        
                        <!-- eTicket Itinerary Receipt -->
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-2">eTicket Itinerary Receipt</h3>
                            <p>The eTicket Itinerary Receipt is non-transferable. It is valid only until the date and time of departure printed on the ticket. Unused or expired eTickets are non-refundable and cannot be revalidated, subject to the Return Policy below.</p>
                        </div>
                        
                        <!-- Government-Mandated Discounts -->
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-2">Government-Mandated Discounts</h3>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-slate-900 mb-1">Senior Citizens</h4>
                                <p class="mb-2">Senior Citizen discounts apply to passengers aged 60 or above once a valid ID number has been entered. The 20% Senior Citizen discount applies to the base rate only.</p>
                                <p class="mb-2">If a promotional rate is available, the lowest applicable eligible fare may be used instead of the Senior Citizen discount. VAT treatment is subject to applicable law and carrier policy.</p>
                                <p class="mb-2">Passengers must present a valid Senior Citizen ID issued by the Office for Senior Citizens Affairs (OSCA), or another valid Philippine government-issued ID showing their date of birth, during inspection and boarding. Failure to present the required ID may result in forfeiture of the discount, revalidation requirements, applicable surcharges, and fare differences.</p>
                                <p class="mb-2">Senior Citizen discounts are applicable to Filipino nationals only.</p>
                                <p>Senior citizens are encouraged to travel with a legal-aged companion and bring a medical certificate confirming fitness to travel. They may be subject to assessment by the vessel doctor or nurse on the departure date. Boarding remains subject to the operating carrier's safety assessment.</p>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-slate-900 mb-1">Infants</h4>
                                <p class="mb-2">Infants below 2 years old and below 1 meter in height may be allowed to board. A medical certificate and documentation proving the infant's relationship to the accompanying passenger may be required.</p>
                                <p class="mb-2">A fixed rate of ₱500.00 applies per infant regardless of destination or accommodation, subject to carrier policy.</p>
                                <p class="mb-2">A separate ticket may be issued for each infant, and the infant may share the parent's or guardian's bunk or room.</p>
                                <p class="mb-2">No more than two infants are allowed per adult passenger. Additional infants may be charged the applicable promotional fare or 75% off the base rate, plus auxiliary charges.</p>
                                <p>Infants must be accompanied by an adult parent or guardian.</p>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-slate-900 mb-1">Pregnant Passengers</h4>
                                <p class="mb-2">Pregnant passengers who are 24 weeks or more into their pregnancy may not be allowed to board.</p>
                                <p>Pregnant passengers may be required to present a medical certificate confirming that gestation is below 24 weeks and are encouraged to travel with a legal-aged companion. The vessel doctor or nurse may assess fitness to travel on the departure date. Final boarding approval remains at the discretion of the operating carrier's medical and safety personnel.</p>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-slate-900 mb-1">Unaccompanied Minor Passengers</h4>
                                <p class="mb-2">An unaccompanied minor is a passenger aged 11 to 17 who travels alone.</p>
                                <p class="mb-2">Children must be accompanied by a parent or legal guardian at the port and endorsed to the boarding officers. The parent or guardian may be required to sign a waiver.</p>
                                <p>Unaccompanied minors must be collected by their declared representative at the destination port. If no representative is present, the passenger may be held for release and endorsed to the Department of Social Welfare and Development (DSWD), as applicable.</p>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-slate-900 mb-1">Other Discounts: PWDs, Students, and Medal of Valor Awardees</h4>
                                <p class="mb-2">Persons with Disabilities (PWDs), students, and Medal of Valor awardees may need to visit Amiga Travel Agency Services corporate ticketing outlets or authorized retail outlets to request special discounts.</p>
                                <p>These discounts apply to the base rate only and generally do not apply to discounted or promotional fares.</p>
                            </div>
                        </div>
                        
                        <!-- Return, Refund, and Revalidation Policy -->
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-2">Return, Refund, and Revalidation Policy</h3>
                            <p class="mb-2">Customers may send refund requests to the official Amiga Travel Agency Services support email using the registered transaction email address. The support email should be configurable via the application settings.</p>
                            <p class="mb-2">Only original eTickets that have not been boarded, remain within ticket validity, and have not undergone rebooking may be eligible for processing.</p>
                            <p class="mb-2">For wallet and credit-card paid transactions, refund requests may be accommodated by email from Monday to Friday, regular business days only, between 8:00 AM and 4:00 PM. Requests must be sent at least two regular days before ticket expiry.</p>
                            <p class="mb-2">For cash and ATM-paid bookings, refunds must be processed through Amiga Travel Agency Services corporate ticketing outlets.</p>
                            <p class="mb-2">For direct refund requests at a corporate ticketing outlet, the account holder or passenger must submit the complete itinerary and present a valid government-issued ID.</p>
                            <p class="mb-2">If an account holder or passenger authorizes a representative, the representative must present:</p>
                            <ul class="list-disc pl-5 space-y-1 mb-2">
                                <li>An original signed authorization letter.</li>
                                <li>The actual valid government-issued ID of the account holder or passenger.</li>
                                <li>The representative's own valid government-issued ID.</li>
                            </ul>
                            <p class="mb-2">Refunds are subject to the following surcharge:</p>
                            <ul class="list-disc pl-5 space-y-1 mb-2">
                                <li>Before vessel departure: ₱600.00 plus the applicable Web Admin Fee per ticket.</li>
                            </ul>
                            <p class="mb-2">No partial refunds are available for tickets purchased under room rates. Refunds are released only after surrendering all tickets issued for the relevant room.</p>
                            <p class="mb-2">Unused and unscanned eTickets may be revalidated during the ticket-validity period. Revalidation means changing ticket details other than the passenger name or age, and the trip origin or destination. Revalidation is processed only through corporate ticketing outlets. Passengers must present the eTicket Itinerary Receipt and a valid ID.</p>
                            <p class="mb-2">Revalidation is subject to the following surcharge:</p>
                            <ul class="list-disc pl-5 space-y-1 mb-2">
                                <li>Before vessel departure: ₱600.00 + ₱36.00 revalidation fee + fare difference.</li>
                            </ul>
                            <p class="mb-2">Refund and revalidation surcharges may be waived if a trip is affected by typhoon, force majeure, technical problems, emergency or extended dry-docking, preventive maintenance, or carrier-initiated trip changes.</p>
                            <p class="mb-2">The Web Admin Fee is non-refundable.</p>
                            <p>Ticket validity ends on the date and time of departure printed on the ticket.</p>
                        </div>
                        
                        <!-- Final Notice -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-sm text-slate-700 font-medium">These terms may be updated by Amiga Travel Agency Services. Please review the current version before each booking.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col gap-3 px-6 py-4 border-t border-slate-200">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" wire:model="hasAcceptedTerms" id="acceptTerms" class="mt-0.5 h-4 w-4 text-[#db2777] border-slate-300 focus:ring-[#db2777]">
                        <span class="text-sm text-slate-700">I have read and agree to the Amiga Travel Agency Services Terms and Conditions.</span>
                    </label>
                    @error('hasAcceptedTerms')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                    
                    <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                        <button type="button" wire:click.prevent="cancelTermsModal" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                            Cancel
                        </button>
                        <button type="button" wire:click.prevent="confirmTermsAndContinue" @disabled($isSubmittingBooking || !$hasAcceptedTerms) class="inline-flex items-center justify-center rounded-xl bg-[#db2777] px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-[#db2777]/90 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            @if ($isSubmittingBooking)
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            @else
                                Done & Continue
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Auto-scroll to first invalid field on validation error -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('validation-error', () => {
                // Wait for DOM updates
                setTimeout(() => {
                    // Find the first element with an error
                    let firstErrorElement = document.querySelector('[aria-invalid="true"]');
                    
                    if (!firstErrorElement) {
                        // Try to find elements with error messages nearby
                        const errorMessages = document.querySelectorAll('.text-rose-600');
                        if (errorMessages.length > 0) {
                            // Check previous siblings for inputs
                            for (let error of errorMessages) {
                                let input = error.previousElementSibling;
                                while (input && !['INPUT', 'SELECT', 'TEXTAREA'].includes(input.tagName)) {
                                    input = input.previousElementSibling;
                                }
                                
                                if (input) {
                                    firstErrorElement = input;
                                    break;
                                }
                            }
                        }
                    }
                    
                    if (!firstErrorElement) {
                        // Fallback: check wire:model errors
                        const inputs = document.querySelectorAll('[wire\\:model]');
                        for (let input of inputs) {
                            const model = input.getAttribute('wire:model');
                            if (model && document.querySelector(`[for="${model}"] ~ .text-rose-600, [wire\\:model="${model}"] ~ .text-rose-600`)) {
                                firstErrorElement = input;
                                break;
                            }
                        }
                    }
                    
                    if (firstErrorElement) {
                        // Scroll the element into view
                        firstErrorElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        
                        // Focus the element if it's focusable
                        if (!['INPUT', 'SELECT', 'TEXTAREA', 'BUTTON'].includes(firstErrorElement.tagName)) {
                            // Try to find a focusable element inside
                            const focusableChild = firstErrorElement.querySelector('input, select, textarea, button');
                            if (focusableChild) {
                                focusableChild.focus();
                            }
                        } else {
                            firstErrorElement.focus();
                        }
                    }
                }, 100);
            });
        });
    </script>
</div>
