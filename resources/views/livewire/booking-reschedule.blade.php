<div class="max-w-4xl mx-auto px-4 py-8">
    @if(!$booking)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-red-900">Booking Not Found</h2>
            <p class="mt-2 text-sm text-red-700">No booking matches reference "#{{ $transaction_number }}". Please check your reference number.</p>
            <a href="{{ route('book.status') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition">
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

                {{-- Disruption Status Pill --}}
                <div class="shrink-0">
                    @if($status === 'rescheduled_approved')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500 px-4 py-2 text-xs font-extrabold text-white shadow-sm">
                            &check; Reschedule Approved
                        </span>
                    @elseif($status === 'reschedule_requested')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500 px-4 py-2 text-xs font-extrabold text-white shadow-sm">
                            &hour-glass; Pending Staff Approval
                        </span>
                    @elseif($status === 'contact_support_required')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-600 px-4 py-2 text-xs font-extrabold text-white shadow-sm">
                            &phone; Contact Support Required
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-600 px-4 py-2 text-xs font-extrabold text-white shadow-sm">
                            Rescheduling Required
                        </span>
                    @endif
                </div>
            </div>

            {{-- Carrier message box --}}
            @if($cancellation && $cancellation->customer_message)
                <div class="mt-6 rounded-2xl border border-amber-200 bg-white/80 backdrop-blur-sm p-4 text-sm text-amber-950 font-medium">
                    <span class="font-bold text-amber-900">Operator Statement:</span> {{ $cancellation->customer_message }}
                </div>
            @endif

            {{-- Highlights grid --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Booking Reference</p>
                    <p class="mt-1 text-base font-extrabold text-slate-900">#{{ $booking->transaction_number }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Service Resume Date</p>
                    <p class="mt-1 text-base font-extrabold text-emerald-700">
                        {{ $cancellation ? $cancellation->resume_date->format('M d, Y') : 'Pending' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Rescheduling Fee</p>
                    <p class="mt-1 text-base font-extrabold text-emerald-800">₱0.00 (Free of Charge)</p>
                </div>
            </div>
        </div>

        {{-- Feedback Alert --}}
        @if($feedback)
            <div class="mb-6 rounded-2xl border p-4 text-sm font-semibold {{ $submitted ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-rose-200 bg-rose-50 text-rose-900' }}">
                {{ $feedback }}
            </div>
        @endif

        {{-- Main Options Grid --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Staff-Approved Replacement Schedules</h2>
                    <p class="text-xs text-slate-500 font-medium">Select a replacement flight or voyage for the route <strong>{{ $booking->origin }} &rarr; {{ $booking->destination }}</strong> starting on or after {{ $cancellation ? $cancellation->resume_date->format('M d, Y') : 'resume date' }}.</p>
                </div>
                
                <button type="button" wire:click="requestSupport" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                    No date works for you? Request Support &rarr;
                </button>
            </div>

            @if($eligibleReplacements->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                    <p class="font-bold text-slate-700">No specific replacement dates listed yet.</p>
                    <p class="mt-1 text-xs text-slate-500 max-w-md mx-auto">Staff are currently updating available replacement voyages for this carrier starting {{ $cancellation ? $cancellation->resume_date->format('M d, Y') : 'soon' }}. You can request support below and our team will contact you directly.</p>
                    <button type="button" wire:click="requestSupport" class="mt-4 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                        Request Staff Assistance
                    </button>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($eligibleReplacements as $item)
                        @php
                            $sch = $item->schedule;
                            $repDate = \Carbon\Carbon::parse($item->replacement_date_formatted);
                            $isSelected = $selected_schedule_id === $sch->id && $selected_date === $item->replacement_date_formatted;
                        @endphp
                        <div
                            wire:click="selectOption({{ $sch->id }}, '{{ $item->replacement_date_formatted }}')"
                            class="group relative rounded-2xl border p-5 cursor-pointer transition-all {{ $isSelected ? 'border-2 border-[#216417] bg-emerald-50/50 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm' }}"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-700 mb-2">
                                        {{ $repDate->format('D, M d, Y') }}
                                    </span>
                                    <h3 class="text-base font-extrabold text-slate-900">{{ $sch->service_name }}</h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $sch->ferryRoute?->operator }} &bull; {{ $sch->formatted_departure }} &rarr; {{ $sch->formatted_arrival }}</p>
                                </div>

                                <div class="shrink-0">
                                    <div class="h-6 w-6 rounded-full border flex items-center justify-center {{ $isSelected ? 'border-[#216417] bg-[#216417] text-white' : 'border-slate-300 bg-white' }}">
                                        @if($isSelected)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Duration: {{ $sch->duration_label }}</span>
                                <span class="font-extrabold text-emerald-700">₱0.00 Extra Fee</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Submit Action Bar --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-slate-900">
                        @if($selected_date)
                            Selected Date: <span class="text-[#216417]">{{ \Carbon\Carbon::parse($selected_date)->format('F d, Y') }}</span>
                        @else
                            <span class="text-slate-400">No replacement option selected yet</span>
                        @endif
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">Staff will review your selection and update your ticket date immediately upon approval.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        wire:click="submitReschedule"
                        @disabled(!$selected_schedule_id || !$selected_date || $status === 'rescheduled_approved')
                        class="rounded-xl bg-[#216417] px-6 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-[#1a5012] disabled:opacity-50 disabled:cursor-not-allowed transition"
                    >
                        Submit Preferred Replacement Date
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
