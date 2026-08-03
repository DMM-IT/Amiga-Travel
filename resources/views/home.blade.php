@extends('layouts.app')

@section('content')
@php
    $showCancelSuggestion = request()->query('show_cancel_suggestion');
    $suggestTxn = request()->query('transaction_number');
@endphp
@if($showCancelSuggestion)
    <div x-data="{ open: true }" x-init="open = true">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
            <div class="relative max-w-lg w-full rounded-2xl bg-white p-6 z-10 shadow-lg relative ws-sbtn-container">
                @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'cancel_modal' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
                <h3 class="text-lg font-semibold text-slate-900">{{ data_get($pageContent, 'cancel_modal_title', 'Want to cancel your booking?') }}</h3>
                <p class="mt-3 text-sm text-slate-700">{{ data_get($pageContent, 'cancel_modal_desc', 'We received your proof of payment. If you change your mind, you can start a 5-minute cancellation window now to request a refund. After 5 minutes, cancellation will no longer be available.') }}</p>
                <div class="mt-4 flex gap-3 justify-end">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ data_get($pageContent, 'cancel_modal_btn_cancel', 'Maybe later') }}</a>
                    <a href="{{ url('/book/status?transaction_number=' . urlencode($suggestTxn) . '&start_cancellation=1') }}" class="inline-flex items-center justify-center rounded-3xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-700">{{ data_get($pageContent, 'cancel_modal_btn_confirm', 'Start cancellation') }}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal for promo image preview --}}
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display:none;">
        <div class="relative max-w-4xl w-full">
            <button @click="modalOpen = false; modalImage = null" class="absolute right-2 top-2 z-20 inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-slate-700 shadow-md hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img :src="modalImage" alt="Promotion" class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl bg-white">
        </div>
    </div>
@endif

{{-- NEW: Airpaz-Style Green Hero Banner --}}
<div class="relative w-full bg-[#008000]">
    @if(session()->has('booking_draft'))
        <div class="w-full bg-pink-50/95 border-b border-pink-200 px-4 sm:px-6 lg:px-8 py-3.5 text-slate-900 shadow-sm relative z-20">
            <div class="max-w-7xl mx-auto flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-pink-700">You have a pending booking in progress.</p>
                    <p class="mt-0.5 text-xs text-slate-600">Return to complete your booking or cancel the draft to start a new one.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ url('/book/new') }}" class="inline-flex items-center justify-center rounded-full bg-pink-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-pink-700">Return to booking</a>
                    <form method="POST" action="{{ route('booking.draft.cancel') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-pink-600 px-4 py-2 text-xs font-semibold text-pink-700 transition hover:bg-pink-100">Cancel draft</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <div class="pt-10 sm:pt-14 lg:pt-16 pb-28 sm:pb-32 lg:pb-36 px-4 sm:px-6 lg:px-8">
        {{-- Header Title & Subtitle --}}
        <div class="max-w-6xl mx-auto text-left relative z-10">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                {{ $pageContent['welcome_title'] ?? 'Welcome to Amiga Gracia' }}
            </h1>
            <p class="mt-3 text-sm sm:text-base text-white/90 max-w-2xl font-medium">
                {{ $pageContent['welcome_subtitle'] ?? 'Your journey deserves more than a destination - it deserves an exceptional experience' }}
            </p>
        </div>
    </div>
</div>

{{-- White Search Box (overlapping Green Hero and White Section below like Airpaz) --}}
<script>
    window.AMIGA_ACTIVE_ROUTES = @json($activeRoutes ?? []);
    window.AMIGA_VEHICLE_RATES = @json($vehicleRates ?? []);
    window.AMIGA_VEHICLE_BRANDS = @json($vehicleBrands ?? []);

    function amigaDatePicker(type) {
        return {
            isOpen: false,
            viewYear: new Date().getFullYear(),
            viewMonth: new Date().getMonth() + 1,
            
            init() {
                let val = type === 'departure' ? this.departure_date : this.return_date;
                if (val) {
                    let parts = val.split('-');
                    if (parts.length === 3) {
                        this.viewYear = parseInt(parts[0], 10);
                        this.viewMonth = parseInt(parts[1], 10);
                    }
                }
                this.$watch(type === 'departure' ? 'departure_date' : 'return_date', (newVal) => {
                    if (newVal) {
                        let parts = newVal.split('-');
                        if (parts.length === 3) {
                            this.viewYear = parseInt(parts[0], 10);
                            this.viewMonth = parseInt(parts[1], 10);
                        }
                    }
                });
                this.$watch(type === 'departure' ? 'enabledDepartureDates' : 'enabledReturnDates', (dates) => {
                    let current = type === 'departure' ? this.departure_date : this.return_date;
                    if (current && dates.length > 0 && !dates.includes(current)) {
                        if (type === 'departure') {
                            this.departure_date = '';
                        } else {
                            this.return_date = '';
                        }
                    }
                });
            },
            
            get isDisabled() {
                if (!this.origin || !this.destination) return true;
                let enabled = type === 'departure' ? this.enabledDepartureDates : this.enabledReturnDates;
                return enabled.length === 0;
            },

            get placeholderText() {
                if (!this.origin || !this.destination) {
                    return 'Select origin & destination first';
                }
                let enabled = type === 'departure' ? this.enabledDepartureDates : this.enabledReturnDates;
                if (enabled.length === 0) {
                    return type === 'departure' ? 'No schedules available' : 'No return schedules available';
                }
                return 'Select date';
            },

            get formattedDate() {
                let val = type === 'departure' ? this.departure_date : this.return_date;
                if (!val) return '';
                let parts = val.split('-');
                if (parts.length !== 3) return val;
                let dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                return dateObj.toLocaleDateString('default', { month: 'short', day: '2-digit', year: 'numeric' });
            },

            get monthLabel() {
                const date = new Date(this.viewYear, this.viewMonth - 1, 1);
                return date.toLocaleString('default', { month: 'long' });
            },

            get minDateStr() {
                if (type === 'return' && this.departure_date) {
                    return this.departure_date;
                }
                let today = new Date();
                let y = today.getFullYear();
                let m = String(today.getMonth() + 1).padStart(2, '0');
                let d = String(today.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            },

            get calendarDays() {
                const firstDay = new Date(this.viewYear, this.viewMonth - 1, 1);
                const startOffset = firstDay.getDay();
                const daysInMonth = new Date(this.viewYear, this.viewMonth, 0).getDate();
                
                let days = Array(startOffset).fill(null);
                let enabled = type === 'departure' ? this.enabledDepartureDates : this.enabledReturnDates;
                let minDate = this.minDateStr;
                
                for (let day = 1; day <= daysInMonth; day++) {
                    const m = String(this.viewMonth).padStart(2, '0');
                    const d = String(day).padStart(2, '0');
                    const dateStr = `${this.viewYear}-${m}-${d}`;
                    
                    let disabled = false;
                    if (dateStr < minDate) {
                        disabled = true;
                    }
                    if (enabled.length > 0 && !enabled.includes(dateStr)) {
                        disabled = true;
                    }
                    
                    days.push({ day, disabled, dateStr });
                }
                
                while (days.length % 7 !== 0) {
                    days.push(null);
                }
                
                return days;
            },

            prevMonth() {
                if (this.viewMonth === 1) {
                    this.viewMonth = 12;
                    this.viewYear--;
                } else {
                    this.viewMonth--;
                }
            },

            nextMonth() {
                if (this.viewMonth === 12) {
                    this.viewMonth = 1;
                    this.viewYear++;
                } else {
                    this.viewMonth++;
                }
            },

            selectDate(dayObj) {
                if (dayObj.disabled) return;
                if (type === 'departure') {
                    this.departure_date = dayObj.dateStr;
                    if (this.return_date && this.return_date < dayObj.dateStr) {
                        this.return_date = '';
                    }
                    this.errors.departure_date = '';
                } else {
                    this.return_date = dayObj.dateStr;
                    this.errors.return_date = '';
                }
                this.isOpen = false;
            }
        };
    }
</script>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30 -mt-16 sm:-mt-20 lg:-mt-20"
     x-data="{
             trip_type: 'one_way',
             mode: 'ferry',
             operator: '',
             origin: '',
             destination: '',
             departure_date: '{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}',
             return_date: '{{ \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d') }}',
             adults: 1,
             children: 0,
             has_vehicle: false,
             vehicleRatesList: window.AMIGA_VEHICLE_RATES || [],
             vehicleBrandsList: window.AMIGA_VEHICLE_BRANDS || [],
             vehicle_booking_method: 'category',
             selected_vehicle_rate_id: '',
             selected_brand_id: '',
             selected_model_id: '',
             vehicle_plate_number: '',
             driver_name: '',
             driver_birthday: '',
             get selectedCargoRate() {
                 if (this.vehicle_booking_method === 'category' && this.selected_vehicle_rate_id) {
                     let r = this.vehicleRatesList.find(x => x.id == this.selected_vehicle_rate_id);
                     return r ? parseFloat(r.price || 0) : 0;
                 }
                 if (this.vehicle_booking_method === 'brand_model' && this.selected_model_id) {
                     let b = this.vehicleBrandsList.find(x => x.id == this.selected_brand_id);
                     if (b && b.models) {
                         let m = b.models.find(x => x.id == this.selected_model_id);
                         return m ? parseFloat(m.price || 0) : 0;
                     }
                 }
                 return 0;
             },
             get availableVehicleModels() {
                 if (!this.selected_brand_id) return [];
                 let b = this.vehicleBrandsList.find(x => x.id == this.selected_brand_id);
                 return b && b.models ? b.models : [];
             },
             showTripTypeDropdown: false,
             showModeDropdown: false,
             showOperatorDropdown: false,
             showPassengerDropdown: false,
             showOriginSuggestions: false,
             showDestinationSuggestions: false,
             showMinorAgeWarning: false,
             hasSeenMinorAgeWarning: false,
             errors: {
                 operator: '',
                 origin: '',
                 destination: '',
                 departure_date: '',
                 return_date: ''
             },
             init() {
                 this.$watch('trip_type', (val) => {
                     if (val === 'round_trip' && this.origin && this.destination) {
                         if (!this.hasReturnRoute(this.origin, this.destination)) {
                             this.destination = '';
                             this.return_date = '';
                         }
                     }
                 });
             },
             hasReturnRoute(origin, destination) {
                 return this.activeRoutes.some(r => 
                     (!this.mode || r.mode === this.mode) && 
                     (!this.operator || r.operator === this.operator) && 
                     r.origin === destination && 
                     r.destination === origin &&
                     (!r.dates || r.dates.length > 0)
                 );
             },
             activeRoutes: window.AMIGA_ACTIVE_ROUTES || [],
             popularPorts: ['Batangas', 'Calapan', 'Caticlan', 'Odiongan', 'Manila', 'Cebu', 'Puerto Princesa', 'Roxas'],
             operatorsList: [
                 { name: '2GO Travel', value: '2GO Travel', logo: '{{ asset('images/2GO-Logo.png') }}', mode: 'ferry' },
                 { name: 'Starlite Ferries Inc.', value: 'Starlite Ferries Inc.', logo: '{{ asset('images/Starlite_Logo.png') }}', mode: 'ferry' },
                 { name: 'Cebu Pacific', value: 'Cebu Pacific', logo: '{{ asset('images/CebuPecific-Logo.png') }}', mode: 'airline' },
                 { name: 'Philippine Airlines', value: 'Philippine Airlines', logo: '{{ asset('images/Pal-Logo.jfif') }}', mode: 'airline' },
                 { name: 'AirAsia', value: 'AirAsia', logo: '{{ asset('images/AirAsia-Logo.png') }}', mode: 'airline' }
             ],
             get filteredOperatorsList() {
                 if (!this.mode) return this.operatorsList;
                 return this.operatorsList.filter(o => o.mode === 'all' || o.mode === this.mode);
             },
             get operatorLabel() {
                 let op = this.operatorsList.find(o => o.value === this.operator);
                 return op ? op.name : 'Select Operator';
             },
             get modeLabel() {
                 if (this.mode === 'ferry') return 'Ferry';
                 if (this.mode === 'airline') return 'Airline';
                 return 'All Modes';
             },
             get availableOrigins() {
                 if (!this.operator) return [];
                 let origins = [];
                 this.activeRoutes.forEach(r => {
                     if ((!this.mode || r.mode === this.mode) && r.operator === this.operator) {
                         if (this.trip_type === 'round_trip' && !this.hasReturnRoute(r.origin, r.destination)) {
                             return;
                         }
                         if (!origins.includes(r.origin)) origins.push(r.origin);
                     }
                 });
                 return origins.sort();
             },
             get availableDestinations() {
                 if (!this.operator || !this.origin) return [];
                 let destinations = [];
                 this.activeRoutes.forEach(r => {
                     if ((!this.mode || r.mode === this.mode) && r.operator === this.operator && r.origin === this.origin) {
                         if (this.trip_type === 'round_trip' && !this.hasReturnRoute(this.origin, r.destination)) {
                             return;
                         }
                         if (!destinations.includes(r.destination)) destinations.push(r.destination);
                     }
                 });
                 return destinations.sort();
             },
              get enabledDepartureDates() {
                  if (!this.origin || !this.destination) return [];
                  let dates = [];
                  this.activeRoutes.forEach(r => {
                      if ((!this.mode || r.mode === this.mode) && 
                          (!this.operator || r.operator === this.operator) && 
                          r.origin === this.origin && 
                          r.destination === this.destination) {
                          if (r.dates && Array.isArray(r.dates)) {
                              r.dates.forEach(d => {
                                  if (!dates.includes(d)) dates.push(d);
                              });
                          }
                      }
                  });
                  return dates.sort();
              },
              get enabledReturnDates() {
                  if (!this.origin || !this.destination) return [];
                  let dates = [];
                  this.activeRoutes.forEach(r => {
                      if ((!this.mode || r.mode === this.mode) && 
                          (!this.operator || r.operator === this.operator) && 
                          r.origin === this.destination && 
                          r.destination === this.origin) {
                          if (r.dates && Array.isArray(r.dates)) {
                              r.dates.forEach(d => {
                                  if (!dates.includes(d)) dates.push(d);
                              });
                          }
                      }
                  });
                  return dates.sort();
              },
              get totalPassengers() {
                 return parseInt(this.adults) + parseInt(this.children);
             },
             swapPorts() {
                 let tmp = this.origin;
                 this.origin = this.destination;
                 this.destination = tmp;
             },
             search() {
                 this.errors.operator = '';
                 this.errors.origin = '';
                 this.errors.destination = '';
                 let hasError = false;
                 if (!this.operator || !this.operator.trim()) {
                     this.errors.operator = 'Please select an operator';
                     hasError = true;
                 }
                 if (!this.origin || !this.origin.trim()) {
                     this.errors.origin = 'Departure city is required';
                     hasError = true;
                 }
                  if (!this.destination || !this.destination.trim()) {
                      this.errors.destination = 'Arrival City is required';
                      hasError = true;
                  }
                  if (!this.departure_date) {
                      this.errors.departure_date = 'Please select a departure date';
                      hasError = true;
                  }
                  if (this.trip_type === 'round_trip' && !this.return_date) {
                      this.errors.return_date = 'Please select a return date';
                      hasError = true;
                  }
                  if (hasError) {
                      setTimeout(() => {
                          this.errors.operator = '';
                          this.errors.origin = '';
                          this.errors.destination = '';
                          this.errors.departure_date = '';
                          this.errors.return_date = '';
                      }, 4000);
                      return;
                  }

                 let params = new URLSearchParams();
                 params.append('trip_type', this.trip_type);
                 if (this.mode) params.append('mode', this.mode);
                 if (this.operator) params.append('operator', this.operator);
                 if (this.origin) params.append('origin', this.origin);
                 if (this.destination) params.append('destination', this.destination);
                 if (this.departure_date) params.append('departure_date', this.departure_date);
                 if (this.trip_type === 'round_trip' && this.return_date) {
                     params.append('return_date', this.return_date);
                 }
                 params.append('adults', this.adults);
                 params.append('children', this.children);
                 params.append('infants', 0);
                 params.append('step', 2);
                 if (this.mode === 'ferry' && this.operator && this.operator.toLowerCase().includes('starlite') && this.has_vehicle) {
                     params.append('has_vehicle', '1');
                     if (this.vehicle_booking_method) params.append('vehicle_booking_method', this.vehicle_booking_method);
                     if (this.vehicle_booking_method === 'category' && this.selected_vehicle_rate_id) {
                         params.append('selected_vehicle_rate_id', this.selected_vehicle_rate_id);
                     }
                     if (this.vehicle_booking_method === 'brand_model') {
                         if (this.selected_brand_id) params.append('selected_brand_id', this.selected_brand_id);
                         if (this.selected_model_id) params.append('selected_model_id', this.selected_model_id);
                     }
                     if (this.vehicle_plate_number) params.append('vehicle_plate_number', this.vehicle_plate_number);
                     if (this.driver_name) params.append('driver_name', this.driver_name);
                     if (this.driver_birthday) params.append('driver_birthday', this.driver_birthday);
                 }
                 
                 window.location.href = '{{ url('/book/new') }}?' + params.toString();
             }
         }"
         @click.away="
             showTripTypeDropdown = false;
             showModeDropdown = false;
             showOperatorDropdown = false;
             showPassengerDropdown = false;
             showOriginSuggestions = false;
             showDestinationSuggestions = false;
         ">
         
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 py-4 px-4 sm:px-6 relative z-10">
            {{-- Top Row: Plain Inline Toolbar Labels (No Boxed Pills, No Divider Line) --}}
            <div class="flex flex-wrap items-center justify-start gap-5 sm:gap-6 mb-3 relative">
                
                <!-- 1. TRIP TYPE Selector -->
                <div class="relative">
                    <button type="button" 
                            @click="showTripTypeDropdown = !showTripTypeDropdown; showModeDropdown = false; showOperatorDropdown = false; showPassengerDropdown = false;"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-700 hover:text-[#216417] transition-colors py-1">
                        <svg class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span x-text="trip_type === 'round_trip' ? 'Round Trip' : 'One Way'">One Way</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showTripTypeDropdown" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         class="absolute left-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                         style="display: none;">
                        <button type="button" @click="trip_type = 'one_way'; showTripTypeDropdown = false;"
                                class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                :class="trip_type === 'one_way' ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                            <span>One Way</span>
                            <svg x-show="trip_type === 'one_way'" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button type="button" @click="trip_type = 'round_trip'; showTripTypeDropdown = false;"
                                class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                :class="trip_type === 'round_trip' ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                            <span>Round Trip</span>
                            <svg x-show="trip_type === 'round_trip'" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- 2. MODE Selector -->
                <div class="relative">
                    <button type="button" 
                            @click="showModeDropdown = !showModeDropdown; showTripTypeDropdown = false; showOperatorDropdown = false; showPassengerDropdown = false;"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-700 hover:text-[#216417] transition-colors py-1">
                        <svg class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                        <span x-text="modeLabel">Ferry</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showModeDropdown" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         class="absolute left-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                         style="display: none;">
                        <button type="button" @click="mode = ''; if(operator && !filteredOperatorsList.some(o => o.value === operator)) operator = ''; showModeDropdown = false;"
                                class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                :class="mode === '' ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                            <span>All Modes</span>
                            <svg x-show="mode === ''" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button type="button" @click="mode = 'ferry'; if(operator && !filteredOperatorsList.some(o => o.value === operator)) operator = ''; showModeDropdown = false;"
                                class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                :class="mode === 'ferry' ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                            <span>Ferry</span>
                            <svg x-show="mode === 'ferry'" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button type="button" @click="mode = 'airline'; if(operator && !filteredOperatorsList.some(o => o.value === operator)) operator = ''; showModeDropdown = false;"
                                class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                :class="mode === 'airline' ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                            <span>Airline</span>
                            <svg x-show="mode === 'airline'" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- 3. OPERATOR Selector -->
                <div class="relative">
                    {{-- Validation Tooltip --}}
                    <div x-show="errors.operator" 
                         x-transition
                         x-cloak
                         class="absolute bottom-full left-0 mb-2 bg-[#3f3f46] text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-xl z-50 whitespace-nowrap">
                        <span x-text="errors.operator"></span>
                        <div class="absolute top-full left-6 -mt-1 border-4 border-transparent border-t-[#3f3f46]"></div>
                    </div>

                    <button type="button" 
                            @click="showOperatorDropdown = !showOperatorDropdown; showTripTypeDropdown = false; showModeDropdown = false; showPassengerDropdown = false; errors.operator = '';"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-700 hover:text-[#216417] transition-colors py-1">
                        <svg class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span x-text="operatorLabel">Select Operator</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showOperatorDropdown" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         class="absolute left-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 max-h-72 overflow-y-auto"
                         style="display: none;">
                        <template x-for="op in filteredOperatorsList" :key="op.value">
                            <button type="button" @click="operator = op.value; showOperatorDropdown = false; errors.operator = ''; if (origin && !availableOrigins.includes(origin)) { origin = ''; destination = ''; } if (destination && !availableDestinations.includes(destination)) { destination = ''; }"
                                    class="w-full text-left px-4 py-2.5 text-sm font-semibold flex items-center justify-between hover:bg-slate-50 transition"
                                    :class="operator === op.value ? 'text-[#216417] bg-emerald-50/50' : 'text-slate-700'">
                                <div class="flex items-center gap-3">
                                    <template x-if="op.logo">
                                        <img :src="op.logo" :alt="op.name" class="w-6 h-6 object-contain">
                                    </template>
                                    <template x-if="!op.logo">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">All</div>
                                    </template>
                                    <span x-text="op.name"></span>
                                </div>
                                <svg x-show="operator === op.value" class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 4. PASSENGER Selector -->
                <div class="relative">
                    <button type="button" 
                            @click="showPassengerDropdown = !showPassengerDropdown; showTripTypeDropdown = false; showModeDropdown = false; showOperatorDropdown = false;"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-700 hover:text-[#216417] transition-colors py-1">
                        <svg class="w-4 h-4 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span><span x-text="totalPassengers"></span> <span x-text="totalPassengers === 1 ? 'Passenger' : 'Passengers'"></span></span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    {{-- Passenger Counter Modal/Dropdown --}}
                    <div x-show="showPassengerDropdown" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         class="absolute left-0 top-full mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 p-5 z-50"
                         style="display: none;">
                        
                        {{-- Adult Row --}}
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-100">
                            <div>
                                <p class="text-sm font-bold text-slate-900">Adults</p>
                                <p class="text-xs text-slate-500">Age 11 and above</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(adults > 1) adults--" 
                                        :disabled="adults <= 1"
                                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#216417] hover:text-[#216417] disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    -
                                </button>
                                <span class="w-5 text-center font-bold text-slate-900" x-text="adults"></span>
                                <button type="button" 
                                        @click="if(totalPassengers < 8) adults++" 
                                        :disabled="totalPassengers >= 8"
                                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#216417] hover:text-[#216417] disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Child Row --}}
                        <div class="flex items-center justify-between py-2.5">
                            <div>
                                <p class="text-sm font-bold text-slate-900">Child</p>
                                <p class="text-xs text-slate-500">Age 2 to 11</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(children > 0) children--" 
                                        :disabled="children <= 0"
                                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#216417] hover:text-[#216417] disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    -
                                </button>
                                <span class="w-5 text-center font-bold text-slate-900" x-text="children"></span>
                                <button type="button" 
                                        @click="if(totalPassengers < 8) { children++; if(!hasSeenMinorAgeWarning) { showMinorAgeWarning = true; hasSeenMinorAgeWarning = true; } }" 
                                        :disabled="totalPassengers >= 8"
                                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#216417] hover:text-[#216417] disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Footer with Done button --}}
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium">Limit 8 passengers</span>
                            <button type="button" 
                                    @click="showPassengerDropdown = false"
                                    class="bg-[#008000] hover:bg-[#006600] text-white font-bold px-6 py-2 rounded-xl text-sm shadow transition">
                                Done
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Bottom Row: Bordered Box Inputs in a Single Compact Horizontal Bar --}}
            <div class="flex flex-col md:flex-row gap-2.5 md:items-stretch w-full min-w-0">
                
                <!-- From (Origin) -->
                <div @click.outside="showOriginSuggestions = false" class="w-full md:flex-1 min-w-0 relative border border-gray-200 rounded-xl px-4 py-2.5 bg-white hover:border-[#216417] focus-within:border-[#216417] focus-within:ring-1 focus-within:ring-[#216417] transition flex flex-col justify-center">
                    {{-- Validation Tooltip --}}
                    <div x-show="errors.origin" 
                         x-transition
                         x-cloak
                         class="absolute bottom-full left-4 mb-2 bg-[#3f3f46] text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-xl z-50 whitespace-nowrap">
                        <span x-text="errors.origin"></span>
                        <div class="absolute top-full left-6 -mt-1 border-4 border-transparent border-t-[#3f3f46]"></div>
                    </div>

                    <label class="text-xs text-gray-400 font-medium block mb-0.5 truncate">From</label>
                    <div class="flex items-center gap-2 w-full min-w-0">
                        <input type="text" 
                               x-model="origin" 
                               @input="errors.origin = ''"
                               @focus="if (!operator) { errors.operator = 'Please select an operator first'; showOperatorDropdown = true; return; } showOriginSuggestions = true; errors.origin = ''" 
                               @click="if (!operator) { errors.operator = 'Please select an operator first'; showOperatorDropdown = true; return; } showOriginSuggestions = true; errors.origin = ''" 
                               placeholder="Origin" 
                               class="w-full min-w-0 bg-transparent text-sm md:text-base font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-none border-0 p-0 truncate">
                    </div>
                    {{-- Origin Suggestions --}}
                    <div x-show="showOriginSuggestions" 
                         class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 max-h-56 overflow-y-auto"
                         style="display: none;">
                        <div class="px-4 py-1.5 text-[11px] font-extrabold text-gray-400 uppercase tracking-wider">Available Origins</div>
                        <template x-if="availableOrigins.length === 0">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500">No schedules found for this operator</div>
                        </template>
                        <template x-for="port in availableOrigins" :key="port">
                            <button type="button" @click="origin = port; showOriginSuggestions = false; errors.origin = '';"
                                    class="w-full text-left px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#216417] transition flex items-center justify-between">
                                <span x-text="port"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Swap Button Circle between Origin & Destination -->
                <div class="flex items-center justify-center shrink-0 -my-2 md:my-auto md:-mx-4 z-20">
                    <button type="button" 
                            @click="swapPorts()" 
                            title="Swap Origin & Destination"
                            class="w-8 h-8 rounded-full bg-[#008000] hover:bg-[#006600] text-white flex items-center justify-center transition-all duration-200 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </button>
                </div>

                <!-- To (Destination) -->
                <div @click.outside="showDestinationSuggestions = false" class="w-full md:flex-1 min-w-0 relative border border-gray-200 rounded-xl px-4 py-2.5 bg-white hover:border-[#216417] focus-within:border-[#216417] focus-within:ring-1 focus-within:ring-[#216417] transition flex flex-col justify-center">
                    {{-- Validation Tooltip --}}
                    <div x-show="errors.destination" 
                         x-transition
                         x-cloak
                         class="absolute bottom-full left-4 mb-2 bg-[#3f3f46] text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-xl z-50 whitespace-nowrap">
                        <span x-text="errors.destination"></span>
                        <div class="absolute top-full left-6 -mt-1 border-4 border-transparent border-t-[#3f3f46]"></div>
                    </div>

                    <label class="text-xs text-gray-400 font-medium block mb-0.5 truncate">To</label>
                    <div class="flex items-center gap-2 w-full min-w-0">
                        <input type="text" 
                               x-model="destination" 
                               @input="errors.destination = ''"
                               @focus="if (!operator) { errors.operator = 'Please select an operator first'; showOperatorDropdown = true; return; } showDestinationSuggestions = true; errors.destination = ''" 
                               @click="if (!operator) { errors.operator = 'Please select an operator first'; showOperatorDropdown = true; return; } showDestinationSuggestions = true; errors.destination = ''" 
                               placeholder="Destination" 
                               class="w-full min-w-0 bg-transparent text-sm md:text-base font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-none border-0 p-0 truncate">
                    </div>
                    {{-- Destination Suggestions --}}
                    <div x-show="showDestinationSuggestions" 
                         class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 max-h-56 overflow-y-auto"
                         style="display: none;">
                        <div class="px-4 py-1.5 text-[11px] font-extrabold text-gray-400 uppercase tracking-wider">Available Destinations</div>
                        <template x-if="!origin">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500">Please select From (Origin) first</div>
                        </template>
                        <template x-if="origin && availableDestinations.length === 0">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500">No destinations available from <span x-text="origin"></span></div>
                        </template>
                        <template x-for="port in availableDestinations" :key="port">
                            <button type="button" @click="destination = port; showDestinationSuggestions = false; errors.destination = '';"
                                    class="w-full text-left px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#216417] transition flex items-center justify-between">
                                <span x-text="port"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Departure Date -->
                <div x-data="amigaDatePicker('departure')" 
                     @click.outside="isOpen = false"
                     class="w-full md:flex-1 min-w-0 relative border border-gray-200 rounded-xl px-4 py-2.5 bg-white hover:border-[#216417] transition flex flex-col justify-center"
                     :class="{ 'opacity-60 cursor-not-allowed bg-slate-50': isDisabled, 'cursor-pointer': !isDisabled }">
                    {{-- Validation Tooltip --}}
                    <div x-show="errors.departure_date" 
                         x-transition
                         x-cloak
                         class="absolute bottom-full left-4 mb-2 bg-[#3f3f46] text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-xl z-50 whitespace-nowrap">
                        <span x-text="errors.departure_date"></span>
                        <div class="absolute top-full left-6 -mt-1 border-4 border-transparent border-t-[#3f3f46]"></div>
                    </div>

                    <label class="text-xs text-gray-400 font-medium block mb-0.5 truncate pointer-events-none">Departure</label>
                    
                    <div @click="if (!isDisabled) isOpen = !isOpen" class="flex items-center justify-between w-full min-w-0 select-none">
                        <span class="truncate text-sm md:text-base font-semibold" 
                              :class="{ 'text-gray-400 font-normal': !departure_date || isDisabled, 'text-gray-800': departure_date && !isDisabled }"
                              x-text="departure_date && !isDisabled ? formattedDate : placeholderText"></span>
                        <svg class="w-4 h-4 text-gray-400 shrink-0 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Custom Calendar Popup --}}
                    <div x-show="isOpen" 
                         x-cloak 
                         x-transition
                         class="absolute left-0 top-full mt-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl z-50 min-w-[280px]"
                         style="display: none;">
                        <div class="flex items-center justify-between text-slate-900 font-bold mb-3">
                            <button type="button" @click.prevent="prevMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                            <div x-text="monthLabel + ' ' + viewYear"></div>
                            <button type="button" @click.prevent="nextMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-slate-500 mb-2">
                            <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-sm">
                            <template x-for="(day, index) in calendarDays" :key="index">
                                <div>
                                    <template x-if="day === null">
                                        <div class="h-10 rounded-lg"></div>
                                    </template>
                                    <template x-if="day !== null">
                                        <button
                                            type="button"
                                            @click.prevent="selectDate(day)"
                                            :disabled="day.disabled"
                                            :class="{
                                                'h-10 rounded-lg transition-colors font-medium flex items-center justify-center w-full': true,
                                                'bg-[#216417] text-white shadow-md': day.dateStr === departure_date,
                                                'bg-slate-50 text-slate-300 cursor-not-allowed line-through': day.disabled && day.dateStr !== departure_date,
                                                'bg-white text-slate-700 hover:bg-slate-100 hover:text-slate-900': !day.disabled && day.dateStr !== departure_date
                                            }"
                                            x-text="day.day"
                                        ></button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Return Date (Only for Round Trip) -->
                <div x-show="trip_type === 'round_trip'" 
                     x-cloak
                     x-data="amigaDatePicker('return')" 
                     @click.outside="isOpen = false"
                     class="w-full md:flex-1 min-w-0 relative border border-gray-200 rounded-xl px-4 py-2.5 bg-white hover:border-[#216417] transition flex flex-col justify-center"
                     :class="{ 'opacity-60 cursor-not-allowed bg-slate-50': isDisabled, 'cursor-pointer': !isDisabled }">
                    {{-- Validation Tooltip --}}
                    <div x-show="errors.return_date" 
                         x-transition
                         x-cloak
                         class="absolute bottom-full left-4 mb-2 bg-[#3f3f46] text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-xl z-50 whitespace-nowrap">
                        <span x-text="errors.return_date"></span>
                        <div class="absolute top-full left-6 -mt-1 border-4 border-transparent border-t-[#3f3f46]"></div>
                    </div>

                    <label class="text-xs text-gray-400 font-medium block mb-0.5 truncate pointer-events-none">Return</label>
                    
                    <div @click="if (!isDisabled) isOpen = !isOpen" class="flex items-center justify-between w-full min-w-0 select-none">
                        <span class="truncate text-sm md:text-base font-semibold" 
                              :class="{ 'text-gray-400 font-normal': !return_date || isDisabled, 'text-gray-800': return_date && !isDisabled }"
                              x-text="return_date && !isDisabled ? formattedDate : placeholderText"></span>
                        <svg class="w-4 h-4 text-gray-400 shrink-0 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Custom Calendar Popup --}}
                    <div x-show="isOpen" 
                         x-cloak 
                         x-transition
                         class="absolute right-0 md:left-0 top-full mt-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl z-50 min-w-[280px]"
                         style="display: none;">
                        <div class="flex items-center justify-between text-slate-900 font-bold mb-3">
                            <button type="button" @click.prevent="prevMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                            <div x-text="monthLabel + ' ' + viewYear"></div>
                            <button type="button" @click.prevent="nextMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-slate-500 mb-2">
                            <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-sm">
                            <template x-for="(day, index) in calendarDays" :key="index">
                                <div>
                                    <template x-if="day === null">
                                        <div class="h-10 rounded-lg"></div>
                                    </template>
                                    <template x-if="day !== null">
                                        <button
                                            type="button"
                                            @click.prevent="selectDate(day)"
                                            :disabled="day.disabled"
                                            :class="{
                                                'h-10 rounded-lg transition-colors font-medium flex items-center justify-center w-full': true,
                                                'bg-[#216417] text-white shadow-md': day.dateStr === return_date,
                                                'bg-slate-50 text-slate-300 cursor-not-allowed line-through': day.disabled && day.dateStr !== return_date,
                                                'bg-white text-slate-700 hover:bg-slate-100 hover:text-slate-900': !day.disabled && day.dateStr !== return_date
                                            }"
                                            x-text="day.day"
                                        ></button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Green Search Button -->
                <div class="w-full md:w-auto shrink-0 flex items-stretch">
                    <button type="button" 
                            @click="search()"
                            class="w-full md:w-auto px-7 py-3 md:py-0 bg-[#008000] hover:bg-[#006600] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 text-base">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Starlite Vehicle Booking Extension Underneath (White Card & Slim Form) -->
        <div x-show="mode === 'ferry' && operator && operator.toLowerCase().includes('starlite')"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2 max-h-0"
             x-transition:enter-end="opacity-100 translate-y-0 max-h-[700px]"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 max-h-[700px]"
             x-transition:leave-end="opacity-0 -translate-y-2 max-h-0"
             x-cloak
             class="mt-3 overflow-hidden"
             style="display: none;">
            <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 shadow-lg text-slate-900">
                <!-- Header / Toggle Row (Screenshot 3 Style) -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-slate-900 font-semibold text-base">Vehicle booking</p>
                        <p class="mt-0.5 text-sm text-slate-600">Add a vehicle to your ferry trip (optional).</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" x-model="has_vehicle" class="peer sr-only" />
                        <span class="relative h-7 w-12 shrink-0 rounded-full bg-slate-200 transition peer-checked:bg-[#db2777] peer-focus:outline-none after:absolute after:left-0.5 after:top-0.5 after:h-6 after:w-6 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-5"></span>
                        <span class="text-sm font-semibold text-slate-700" x-text="has_vehicle ? 'Yes' : 'No'">No</span>
                    </label>
                </div>

                <!-- Slim Vehicle Booking Form (Screenshot 2 Style made Slim) -->
                <div x-show="has_vehicle"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2 max-h-0"
                     x-transition:enter-end="opacity-100 translate-y-0 max-h-[500px]"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 max-h-[500px]"
                     x-transition:leave-end="opacity-0 -translate-y-2 max-h-0"
                     class="mt-4 pt-4 border-t border-slate-200">
                    <!-- Row 1: 4 slim columns -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                        <!-- Col 1: Classify Cargo by -->
                        <div>
                            <span class="text-xs font-semibold text-slate-700 block mb-1.5">Classify Cargo by:</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" 
                                        @click="vehicle_booking_method = 'category'"
                                        :class="vehicle_booking_method === 'category' ? 'border-[#db2777] bg-[#db2777]/5 text-[#db2777] font-bold' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                                        class="flex-1 h-9 px-3 rounded-lg border text-xs sm:text-sm transition flex items-center justify-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full border flex items-center justify-center" :class="vehicle_booking_method === 'category' ? 'border-[#db2777]' : 'border-slate-300'">
                                        <span x-show="vehicle_booking_method === 'category'" class="w-1.5 h-1.5 rounded-full bg-[#db2777]"></span>
                                    </span>
                                    <span>Category</span>
                                </button>
                                <button type="button" 
                                        @click="vehicle_booking_method = 'brand_model'"
                                        :class="vehicle_booking_method === 'brand_model' ? 'border-[#db2777] bg-[#db2777]/5 text-[#db2777] font-bold' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                                        class="flex-1 h-9 px-3 rounded-lg border text-xs sm:text-sm transition flex items-center justify-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full border flex items-center justify-center" :class="vehicle_booking_method === 'brand_model' ? 'border-[#db2777]' : 'border-slate-300'">
                                        <span x-show="vehicle_booking_method === 'brand_model'" class="w-1.5 h-1.5 rounded-full bg-[#db2777]"></span>
                                    </span>
                                    <span>Brand</span>
                                </button>
                            </div>
                        </div>

                        <!-- Col 2: Category or Brand/Model Dropdown -->
                        <div>
                            <template x-if="vehicle_booking_method === 'category'">
                                <div>
                                    <span class="text-xs font-semibold text-slate-700 block mb-1.5">Category *</span>
                                    <select x-model="selected_vehicle_rate_id" 
                                            class="w-full h-9 px-3 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20">
                                        <option value="">Select category</option>
                                        <template x-for="rate in vehicleRatesList" :key="rate.id">
                                            <option :value="rate.id" x-text="rate.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </template>
                            <template x-if="vehicle_booking_method === 'brand_model'">
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div>
                                        <span class="text-xs font-semibold text-slate-700 block mb-1.5">Brand *</span>
                                        <select x-model="selected_brand_id" 
                                                @change="selected_model_id = ''"
                                                class="w-full h-9 px-2 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20">
                                            <option value="">Brand</option>
                                            <template x-for="brand in vehicleBrandsList" :key="brand.id">
                                                <option :value="brand.id" x-text="brand.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <span class="text-xs font-semibold text-slate-700 block mb-1.5">Model *</span>
                                        <select x-model="selected_model_id" 
                                                :disabled="!selected_brand_id"
                                                class="w-full h-9 px-2 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20 disabled:opacity-50">
                                            <option value="">Model</option>
                                            <template x-for="model in availableVehicleModels" :key="model.id">
                                                <option :value="model.id" x-text="model.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Col 3: Plate Number -->
                        <div>
                            <span class="text-xs font-semibold text-slate-700 block mb-1.5">Plate Number *</span>
                            <input type="text" 
                                   x-model="vehicle_plate_number" 
                                   placeholder="e.g., ABC 1234" 
                                   class="w-full h-9 px-3 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20" />
                        </div>

                        <!-- Col 4: Cargo Rate -->
                        <div>
                            <span class="text-xs font-semibold text-slate-700 block mb-1.5">Cargo Rate</span>
                            <div class="h-9 rounded-lg border border-slate-200 bg-slate-100/80 px-3 flex items-center justify-center text-sm font-extrabold text-slate-900">
                                <span x-text="'₱' + selectedCargoRate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Driver Details (Slim 2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div>
                            <span class="text-xs font-semibold text-slate-700 block mb-1.5">Driver name</span>
                            <input type="text" 
                                   x-model="driver_name" 
                                   placeholder="e.g., Juan Dela Cruz" 
                                   class="w-full h-9 px-3 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-700 block mb-1.5">Driver birthday</span>
                            <input type="date" 
                                   x-model="driver_birthday" 
                                   class="w-full h-9 px-3 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:border-[#db2777] focus:outline-none focus:ring-1 focus:ring-[#db2777]/20" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minor Age Reminder Modal -->
        <div x-show="showMinorAgeWarning"
             x-cloak
             x-transition
             @click.self="showMinorAgeWarning = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
             style="display: none;">
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white p-6 shadow-2xl text-left">
                <button type="button" @click="showMinorAgeWarning = false" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100">
                    <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="sr-only">Close</span>
                </button>

                <h2 class="text-xl font-bold text-slate-900">Minor age reminder</h2>
                <p class="mt-3 text-slate-600">23 months and under will be issued upon arrival at the port.</p>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="showMinorAgeWarning = false" class="inline-flex rounded-full bg-[#db2777] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#be185d]">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Promo image section removed — integrated into promotions grid (landscape + portrait) --}}

    <div class="max-w-7xl mx-auto px-4 mt-6 sm:mt-8 relative z-20">
        <div class="text-center mb-6 pt-2">
            <h2 class="text-xl font-black text-[#216417]">Manage your booking</h2>
            <p class="text-sm text-black font-semibold mt-3">Quickly access your booking details, changes and refunds.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ url('/book/status') }}" class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm transition hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-red-50 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M7 21h10a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h4" />
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="text-base font-semibold text-slate-900">Change Schedule</h3>
                        <p class="mt-1 text-xs text-slate-600">Reschedule your travel with ease.</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ url('/book/status') }}" class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm transition hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-pink-50 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3h6c0-1.657-1.343-3-3-3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v5m0 0l3-3m-3 3l-3-3" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 1116 0 8 8 0 01-16 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <h3 class="text-base font-semibold text-slate-900">Refund</h3>
                        <p class="mt-1 text-xs text-slate-600">Request a refund for your booking.</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>
    </div>

    @php
        // If not already defined earlier, prepare promo slides
        if (!isset($__promo_slides)) {
            $__promo_files = glob(public_path('images/prmotion_images/*.{jpg,jpeg,png,gif}'), GLOB_BRACE) ?: [];
            $__promo_slides = array_map(function($f){ $name = pathinfo($f, PATHINFO_FILENAME); return ['title' => ucwords(str_replace(['-','_'], ' ', $name)), 'subtitle' => '', 'image' => asset('images/prmotion_images/' . basename($f))]; }, $__promo_files);
        }
    @endphp
    <div class="max-w-7xl mx-auto px-4 mt-10 amiga-animate-on-scroll amiga-transition" x-data='{ currentSlide: 0, slides: @json($__promo_slides), modalOpen: false, modalImage: null }' x-init="console.log('promotions slides', slides); if (slides && slides.length) { setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000); }">
        <div class="mb-4 text-center">
            <h2 class="text-xl font-black text-[#216417]">{{ data_get($pageContent, 'promo_gallery_title', 'Featured Promotions') }}</h2>
            <p class="text-sm text-black font-semibold mt-2">{{ data_get($pageContent, 'promo_gallery_subtitle', 'Browse three highlighted offers from our latest deals.') }}</p>
        </div>
        <div class="grid gap-6 lg:grid-cols-[2fr_1fr] items-stretch">
            <div class="rounded-[1.5rem] border border-slate-200 bg-white/95 shadow-lg overflow-hidden p-6 h-full flex flex-col">
                <div class="rounded-[1.5rem] overflow-hidden bg-slate-100 relative flex-1 min-h-0">
                    <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-xl font-semibold">Landscape video placeholder</div>
                    <div class="absolute inset-0 bg-black/10"></div>
                </div>
            </div>

            <div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white/95 shadow-lg overflow-hidden relative group">
                    <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="currentSlide === index" x-transition.opacity.duration.700 class="absolute inset-0 flex items-center justify-center bg-slate-100">
                                <img :src="slide.image || slide" alt="" @click="modalImage = (slide.image || slide); modalOpen = true; console.log('opening modal', (slide.image || slide))" class="max-h-full max-w-full object-contain object-center cursor-zoom-in" onerror="console.error('promo image failed to load', this.src); this.onerror=null; this.src='https://via.placeholder.com/400x600?text=Promo';">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white">
                                    <div class="font-semibold" x-text="slide.title"></div>
                                    <div class="text-xs text-white/80 mt-1" x-text="slide.subtitle"></div>
                                </div>
                            </div>
                        </template>

                        {{-- Prev/Next buttons (schedule-style) --}}
                        <button x-show="slides.length > 1" @click="currentSlide = (currentSlide === 0 ? slides.length - 1 : currentSlide - 1)" class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 flex items-center justify-center text-slate-600 hover:text-[#216417] hover:border-[#216417] transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0" :disabled="slides.length <= 1">
                            <svg class="w-5 h-5 pr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button x-show="slides.length > 1" @click="currentSlide = (currentSlide === slides.length - 1 ? 0 : currentSlide + 1)" class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 flex items-center justify-center text-slate-600 hover:text-[#216417] hover:border-[#216417] transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0" :disabled="slides.length <= 1">
                            <svg class="w-5 h-5 pl-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Booking Request Cards --}}
<div class="max-w-7xl mx-auto px-4 pb-12 mt-10 amiga-animate-on-scroll amiga-transition">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-black text-[#216417]">
            {{ data_get($pageContent, 'booking_section_title', 'Request Travel Bookings') }}
        </h2>

        <p class="text-sm text-black font-semibold mt-2">
            {{ data_get($pageContent, 'booking_section_description', 'Kay Amiga, Hassle Free Ka! Select a booking category to start your transaction request.') }}
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
        @php
            $bookingCards = data_get($pageContent, 'content.booking_cards', data_get($pageContent, 'booking_cards', []));
            $defaultBookingCards = [
                [
                    'title' => '2GO Travel',
                    'description' => 'Book premier overnight ship accommodation and fast cargo transits with 2GO Travel.',
                    'image' => 'images/2GO-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('2GO Travel') . '&trip_type=one_way&mode=ferry',
                ],
                [
                    'title' => 'Starlite Ferries Inc.',
                    'description' => 'Affordable regional ferry departures between Batangas, Calapan, and Roxas.',
                    'image' => 'images/Starlite_Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Starlite Ferries Inc.') . '&trip_type=one_way&mode=ferry',
                ],
                [
                    'title' => 'Cebu Pacific',
                    'description' => 'Search daily flights and budget fares across the Philippines and Asia.',
                    'image' => 'images/CebuPecific-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Cebu Pacific') . '&trip_type=one_way&mode=airline',
                ],
                [
                    'title' => 'Philippine Airlines',
                    'description' => 'Book Philippine Airlines flights with premium support and flexible fare options.',
                    'image' => 'images/Pal-Logo.jfif',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Philippine Airlines') . '&trip_type=one_way&mode=airline',
                ],
                [
                    'title' => 'AirAsia',
                    'description' => 'Find low-cost airline tickets and convenient domestic connections.',
                    'image' => 'images/AirAsia-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('AirAsia') . '&trip_type=one_way&mode=airline',
                ],
            ];

            $totalCardsNeeded = 5;
            $cards = ! empty($bookingCards) ? array_values($bookingCards) : $defaultBookingCards;
            $cards = array_slice($cards, 0, $totalCardsNeeded);

            while (count($cards) < $totalCardsNeeded) {
                $cards[] = [
                    'title' => 'Travel Booking',
                    'description' => 'Kasiyahan po namin ang paglingkuran kayo.',
                    'image' => null,
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new',
                ];
            }
        @endphp

        @foreach($cards as $card)
            @php
                $rawCardImage = data_get($card, 'image');
                
                if (is_array($rawCardImage)) {
                    $rawCardImage = array_values(array_filter($rawCardImage))[0] ?? null;
                }

                $cardImage = $rawCardImage
                    ? (str_starts_with($rawCardImage, 'http://') || str_starts_with($rawCardImage, 'https://')
                        ? $rawCardImage
                        : (str_starts_with($rawCardImage, 'images/')
                            ? asset($rawCardImage)
                            : (storage_asset_path($rawCardImage) ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80')))
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';

                $cardTitle = data_get($card, 'title', 'Travel Booking');
                $cardDescription = data_get($card, 'description', 'Kasiyahan po namin ang paglingkuran kayo.');
                $cardLink = data_get($card, 'link', '/book/new');
                $bookingText = data_get($card, 'booking_button_text', 'Book Now');
            @endphp
            <a href="{{ url($cardLink) }}" class="group rounded-xl sm:rounded-[2rem] bg-white border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col overflow-hidden">
                <div class="h-20 sm:h-36 w-full bg-white flex items-center justify-center p-2 sm:p-8 border-b border-slate-100">
                    <img src="{{ $cardImage }}" alt="{{ $cardTitle }}" class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';">
                </div>
                <div class="p-2.5 sm:p-6 flex flex-col flex-grow">
                    <span class="inline-flex items-center gap-1 text-[8px] sm:text-[10px] font-semibold text-[#ee018d] uppercase tracking-wider mb-1 sm:mb-3 leading-tight truncate">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 15v-4H7l5-7v4h4l-5 7z"/>
                        </svg>
                        <span class="truncate">Amiga - Best Travel Buddy</span>
                    </span>
                    <h3 class="text-xs sm:text-lg font-bold text-slate-900 mb-1 sm:mb-2 leading-tight truncate">{{ $cardTitle }}</h3>
                    <p class="text-[9px] sm:text-sm text-slate-600 mb-2 sm:mb-4 flex-grow line-clamp-2 sm:line-clamp-none leading-tight">{{ $cardDescription }}</p>
                    <button class="w-full bg-[#ee018d] text-white text-[10px] sm:text-sm font-bold py-1.5 px-2 sm:py-3 sm:px-6 rounded-full hover:bg-pink-700 transition-colors leading-tight">
                        {{ $bookingText }}
                    </button>
                </div>
            </a>
        @endforeach
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var animatedSections = document.querySelectorAll('.amiga-animate-on-scroll');
        if (!('IntersectionObserver' in window) || animatedSections.length === 0) {
            animatedSections.forEach(function (el) {
                el.classList.add('amiga-visible');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('amiga-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
        });

        animatedSections.forEach(function (el) {
            observer.observe(el);
        });
    });
</script>

<style>
    .amiga-transition {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.65s ease, transform 0.65s ease;
        will-change: opacity, transform;
    }

    .amiga-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>
@endsection
