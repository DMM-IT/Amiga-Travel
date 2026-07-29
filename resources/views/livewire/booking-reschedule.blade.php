<div class="max-w-4xl mx-auto px-4 py-8">
    @if(!$booking)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center">
            <h2 class="text-xl font-bold text-red-900">Booking Not Found</h2>
            <p class="mt-2 text-sm text-red-700">No booking matches reference "#{{ $transaction_number }}".</p>
            <a href="{{ route('book.status') }}" class="mt-6 inline-block rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition">
                Return to Booking Status
            </a>
        </div>
    @else
        @php
            $cancellation = $booking->serviceCancellation;
            $status = $booking->disruption_status;
        @endphp

        {{-- Disruption Banner --}}
        <div class="overflow-hidden rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-500/10 via-amber-50 to-orange-50 p-6 sm:p-8 shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-700" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Unavoidable Schedule Disruption Notice
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Select Replacement Travel Date</h1>
                    <p class="mt-2 text-sm text-slate-700 font-medium max-w-2xl">
                        Your original {{ $booking->schedule_service ?? 'travel' }} voyage on <strong>{{ $booking->departure_date->format('M d, Y') }}</strong> was cancelled by <strong>{{ $cancellation->carrier ?? 'the operator' }}</strong> due to {{ $cancellation ? strtolower(str_replace('_', ' ', $cancellation->reason_category)) : 'unavoidable advisories' }}.
                    </p>
                </div>
            </div>

            {{-- Carrier message box --}}
            @if($cancellation && $cancellation->customer_message)
                <div class="mt-6 rounded-2xl border border-amber-200 bg-white/80 backdrop-blur-sm p-4 text-sm text-amber-950 font-medium">
                    <span class="font-bold text-amber-900">Operator Statement:</span> {{ $cancellation->customer_message }}
                </div>
            @endif

            {{-- Highlights grid --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Booking Reference</p>
                    <p class="mt-1 text-base font-extrabold text-slate-900">#{{ $booking->transaction_number }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Service Resume Date</p>
                    <p class="mt-1 text-base font-extrabold {{ $cancellation && $cancellation->resume_date ? 'text-emerald-700' : 'text-amber-600' }}">
                        {{ $cancellation && $cancellation->resume_date ? $cancellation->resume_date->format('M d, Y') : 'To Be Announced' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Feedback Alert --}}
        @if($feedback)
            <div class="mb-6 rounded-2xl border p-4 text-sm font-semibold {{ $submitted ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-rose-200 bg-rose-50 text-rose-900' }}">
                {{ $feedback }}
            </div>
        @endif

        @if($submitted)
            <div class="text-center py-12">
                <a href="{{ route('book.status', ['transaction_number' => $booking->transaction_number]) }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition">
                    View Booking Status
                </a>
            </div>
        @else

            {{-- Cancel & Refund Form --}}
            @if($showRefundForm)
                <div class="rounded-3xl border border-rose-200 bg-white p-6 shadow-xl mb-8 relative">
                    <button wire:click="closeRefundForm" type="button" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="mb-6">
                        <h2 class="text-xl font-black text-rose-700">Cancel & Request 100% Refund</h2>
                        <p class="mt-1 text-sm text-slate-600">Since this cancellation was caused by the operator, you are entitled to a full refund of <strong>₱{{ number_format($booking->total_price, 2) }}</strong>.</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Refund Method</label>
                            <select wire:model.live="refund_method" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium focus:border-rose-500 focus:ring-rose-500">
                                <option value="GCash">GCash</option>
                                <option value="Online Wallet">Other Online Wallet</option>
                                <option value="Bank Account">Bank Account</option>
                            </select>
                        </div>

                        @if(in_array($refund_method, ['Bank Account', 'Online Wallet']))
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Institution Name</label>
                                <input type="text" wire:model="refund_bank_name" placeholder="e.g. BDO, Maya" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium focus:border-rose-500 focus:ring-rose-500">
                                @error('refund_bank_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Account Number / Mobile</label>
                            <input type="text" wire:model="refund_account_number" placeholder="e.g. 09123456789" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium focus:border-rose-500 focus:ring-rose-500">
                            @error('refund_account_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Account Name</label>
                            <input type="text" wire:model="refund_account_name" placeholder="e.g. Juan Dela Cruz" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium focus:border-rose-500 focus:ring-rose-500">
                            @error('refund_account_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
                        <button wire:click="closeRefundForm" type="button" class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button wire:click="submitCancelAndRefund" type="button" class="rounded-xl bg-rose-600 px-8 py-3 text-sm font-bold text-white hover:bg-rose-700 shadow-sm transition">Submit Refund Request</button>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900">Replacement Booking</h2>
                @if(!$showRefundForm)
                    <button wire:click="openRefundForm" type="button" class="text-sm font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        Or Cancel &amp; Refund instead
                    </button>
                @endif
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">

                {{-- WIZARD STEP 1: DEPARTURE SCHEDULE --}}
                @if($step === 'departure_date')
                    <div class="mb-6 pb-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Step 1: Pick a Departure Date</h3>
                        <input type="date" wire:model.live="dep_date" min="{{ $cancellation && $cancellation->resume_date ? $cancellation->resume_date->format('Y-m-d') : today()->format('Y-m-d') }}" class="w-full max-w-xs rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Available Schedules for {{ \Carbon\Carbon::parse($dep_date)->format('M d, Y') }}</h4>
                        @if($this->availableDepartureSchedules->isEmpty())
                            <div class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">No schedules available on this date for this route.</div>
                        @else
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($this->availableDepartureSchedules as $sch)
                                    <div wire:click="selectDepartureSchedule({{ $sch->id }}, {{ $sch->price }})" class="group cursor-pointer rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="font-black text-slate-900">{{ $sch->formatted_departure }} &mdash; {{ $sch->formatted_arrival }}</h5>
                                                <p class="text-xs font-bold text-emerald-700">{{ $sch->ferryRoute->operator }}</p>
                                                <p class="text-xs text-slate-500 mt-1">{{ $sch->ferryRoute->origin }} &rarr; {{ $sch->ferryRoute->destination }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- WIZARD STEP 2: DEPARTURE ACCOMMODATION --}}
                @if($step === 'departure_accommodation')
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Step 2: Departure Accommodation</h3>
                        <button wire:click="setStep('departure_date')" class="text-sm font-semibold text-emerald-600 hover:underline">Change Schedule</button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($this->departureAccommodations as $acc)
                            <div wire:click="selectDepartureAccommodation('{{ $acc->id }}', {{ $acc->price }})" class="group cursor-pointer rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-md transition text-center">
                                <h4 class="font-bold text-slate-900">{{ $acc->name }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $acc->description }}</p>
                                <p class="mt-3 font-black text-emerald-700">₱{{ number_format($acc->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                    @if($this->departureAccommodations->isEmpty())
                        <div class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">No accommodations listed for this schedule. Please go back and choose another.</div>
                    @endif
                @endif

                {{-- WIZARD STEP 3: RETURN SCHEDULE (Round Trip Only) --}}
                @if($step === 'return_date')
                    <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-6">
                        <h3 class="text-lg font-bold text-slate-900">Step 3: Pick a Return Date</h3>
                        <button wire:click="setStep('departure_accommodation')" class="text-sm font-semibold text-emerald-600 hover:underline">&larr; Back</button>
                    </div>
                    <div class="mb-6">
                        <input type="date" wire:model.live="ret_date" min="{{ $dep_date }}" class="w-full max-w-xs rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Available Return Schedules for {{ \Carbon\Carbon::parse($ret_date)->format('M d, Y') }}</h4>
                        @if($this->availableReturnSchedules->isEmpty())
                            <div class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">No schedules available on this date for this route.</div>
                        @else
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($this->availableReturnSchedules as $sch)
                                    <div wire:click="selectReturnSchedule({{ $sch->id }}, {{ $sch->price }})" class="group cursor-pointer rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="font-black text-slate-900">{{ $sch->formatted_departure }} &mdash; {{ $sch->formatted_arrival }}</h5>
                                                <p class="text-xs font-bold text-emerald-700">{{ $sch->ferryRoute->operator }}</p>
                                                <p class="text-xs text-slate-500 mt-1">{{ $sch->ferryRoute->origin }} &rarr; {{ $sch->ferryRoute->destination }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- WIZARD STEP 4: RETURN ACCOMMODATION --}}
                @if($step === 'return_accommodation')
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Step 4: Return Accommodation</h3>
                        <button wire:click="setStep('return_date')" class="text-sm font-semibold text-emerald-600 hover:underline">Change Schedule</button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($this->returnAccommodations as $acc)
                            <div wire:click="selectReturnAccommodation('{{ $acc->id }}', {{ $acc->price }})" class="group cursor-pointer rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-md transition text-center">
                                <h4 class="font-bold text-slate-900">{{ $acc->name }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $acc->description }}</p>
                                <p class="mt-3 font-black text-emerald-700">₱{{ number_format($acc->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- WIZARD STEP 5: CONFIRM --}}
                @if($step === 'confirm')
                    <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-slate-900">Review &amp; Confirm</h3>
                        <button wire:click="setStep('{{ $this->isRoundTrip() ? 'return_accommodation' : 'departure_accommodation' }}')" class="text-sm font-semibold text-emerald-600 hover:underline">&larr; Back to edits</button>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-2xl bg-slate-50 p-6">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Departure Details</h4>
                            <p class="font-medium text-slate-900">Date: {{ \Carbon\Carbon::parse($dep_date)->format('M d, Y') }}</p>
                            <p class="text-sm text-slate-600">Passengers: {{ $booking->passengers()->count() ?: 1 }}</p>
                        </div>
                        
                        @if($this->isRoundTrip())
                            <div class="rounded-2xl bg-slate-50 p-6">
                                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Return Details</h4>
                                <p class="font-medium text-slate-900">Date: {{ \Carbon\Carbon::parse($ret_date)->format('M d, Y') }}</p>
                            </div>
                        @endif

                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-800 mb-4">Price Computation</h4>
                            <div class="flex justify-between text-sm text-emerald-900 mb-2">
                                <span>Original Booking Total</span>
                                <span>₱{{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-emerald-900 pt-2 border-t border-emerald-200">
                                <span>Difference to Pay</span>
                                <span>₱{{ number_format($priceDiff, 2) }}</span>
                            </div>
                        </div>

                        @if($priceDiff > 0)
                            <div class="rounded-2xl border border-amber-200 bg-white p-6 shadow-sm">
                                <h4 class="text-base font-bold text-slate-900 mb-2">Additional Payment Required</h4>
                                <p class="text-sm text-slate-600 mb-6">Since your new selections cost more than your original booking, please pay the difference of <strong>₱{{ number_format($priceDiff, 2) }}</strong> via GCash and upload the receipt below.</p>
                                
                                <div class="flex items-center gap-6 mb-6">
                                    <div class="h-32 w-32 shrink-0 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center p-2">
                                        {{-- Mock QR code --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h-2v2h-2v2h2v-2h2v-2zm-2 4h-2v2h-2v2h2v-2h2v-2zm-2 4h-2v2h2v-2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">GCash / Maya</p>
                                        <p class="text-sm text-slate-600">Number: 0917 123 4567</p>
                                        <p class="text-sm text-slate-600">Name: Amiga Gracia Travel</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Payment Receipt</label>
                                    <input type="file" wire:model="paymentProof" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                                    @error('paymentProof') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end pt-4">
                            <button wire:click="submitReschedule" wire:loading.attr="disabled" class="rounded-xl bg-[#216417] px-8 py-3.5 text-sm font-extrabold text-white shadow-sm hover:bg-[#1a5012] disabled:opacity-50 transition">
                                Submit Reschedule Request
                            </button>
                        </div>
                    </div>
                @endif
            </div>

        @endif
    @endif
</div>
