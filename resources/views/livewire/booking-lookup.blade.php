<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-8 sm:px-10" style="background: linear-gradient(135deg, #ee018d 0%, #b1015d 100%);">
                <a href="{{ url('/book') }}" class="text-white/80 text-sm hover:text-white">← Back</a>
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold text-white">Check My Booking</h1>
                <p class="mt-2 text-white/85">Enter your transaction number to view your booking details.</p>
            </div>

            <div class="p-6 sm:p-10 space-y-6">
                <form wire:submit.prevent="search" class="flex flex-col sm:flex-row gap-3">
                    <label class="block flex-1">
                        <span class="sr-only">Transaction number</span>
                        <input
                            type="text"
                            wire:model.defer="transaction_number"
                            placeholder="e.g. AGT-20260701-1234"
                            class="block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2"
                            style="--tw-ring-color:#ee018d;"
                        />
                        @error('transaction_number')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition" style="background:#ee018d;" onmouseover="this.style.background='#c30172'" onmouseout="this.style.background='#ee018d'">
                        Search
                    </button>
                </form>

                @if($searched)
                    @if($booking)
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                'confirmed' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                            ];
                            $statusStyle = $statusColors[$booking->status] ?? $statusColors['pending'];
                        @endphp
                        @if($feedback)
                            <div class="rounded-3xl border border-pink-200 bg-pink-50 p-4 text-sm text-pink-700">
                                {{ $feedback }}
                            </div>
                        @endif

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 space-y-6">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm text-slate-500">Transaction Number</p>
                                    <p class="text-lg font-semibold text-slate-900">{{ $booking->transaction_number }}</p>
                                </div>
                                <span class="rounded-full px-4 py-1.5 text-sm font-semibold" @style(['background' => $statusStyle['bg'], 'color' => $statusStyle['text']])>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                    <p class="text-sm text-slate-500">Route</p>
                                    <p class="font-medium text-slate-900">{{ $booking->origin }} → {{ $booking->destination }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                    <p class="text-sm text-slate-500">Travel Dates</p>
                                    <p class="font-medium text-slate-900">{{ $booking->departure_date->format('M d, Y') }}{{ $booking->return_date ? ' → ' . $booking->return_date->format('M d, Y') : ' (One-way)' }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                    <p class="text-sm text-slate-500">Ferry Schedule</p>
                                    <p class="font-medium text-slate-900">{{ $booking->schedule_summary ?? 'Not recorded' }}</p>
                                    @if($booking->schedule_price)
                                        <p class="text-sm text-slate-600 mt-1">₱{{ number_format($booking->schedule_price, 2) }} per passenger{{ $booking->return_date ? ' (round trip)' : '' }}</p>
                                    @endif
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                    <p class="text-sm text-slate-500">Booked by</p>
                                    <p class="font-medium text-slate-900">{{ $booking->client_name }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                    <p class="text-sm text-slate-500">Payment Status</p>
                                    <p class="font-medium text-slate-900">{{ $booking->transaction ? ucfirst($booking->transaction->payment_status) : 'N/A' }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-semibold text-slate-900 mb-3">Passengers</h3>
                                <div class="space-y-2">
                                    @foreach($booking->passengers as $passenger)
                                        <div class="rounded-2xl bg-white p-4 border border-slate-200 flex items-center justify-between">
                                            <span class="text-slate-800">{{ ucfirst($passenger->type) }}{{ $passenger->name ? ' — ' . $passenger->name : '' }}</span>
                                            <span class="text-sm text-slate-600">{{ $passenger->discount->name ?? 'No discount' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($booking->accommodations->isNotEmpty())
                                <div>
                                    <h3 class="font-semibold text-slate-900 mb-3">Accommodations</h3>
                                    <div class="space-y-2">
                                        @foreach($booking->accommodations as $accommodation)
                                            <div class="rounded-2xl bg-white p-4 border border-slate-200 flex items-center justify-between">
                                                <span class="text-slate-800">{{ $accommodation->name }}</span>
                                                <span class="text-sm text-slate-600">₱{{ number_format($accommodation->pivot->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="rounded-2xl p-4 flex items-center justify-between" style="background:#eaf5e8;">
                                <span class="font-semibold text-slate-900">Total Price</span>
                                <span class="text-lg font-semibold" style="color:#216417;">₱{{ number_format($booking->total_price, 2) }}</span>
                            </div>

                            <div class="space-y-4">
                                <div class="flex flex-wrap gap-3">
                                    @if($booking->transaction && in_array($booking->transaction->payment_status, ['pending', 'unpaid'], true) && $booking->status === 'pending')
                                        <a href="{{ route('payment.show', $booking->transaction) }}" class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition" style="background:#ee018d;" onmouseover="this.style.background='#c30172'" onmouseout="this.style.background='#ee018d'">
                                            Complete Payment
                                        </a>

                                        @if(! $cancellationRequested && ! $cancellationExpired)
                                            <button wire:click.prevent="requestCancellation" type="button" class="inline-flex items-center justify-center rounded-3xl border border-pink-500 px-6 py-3 text-sm font-semibold text-pink-700 transition hover:bg-pink-50">
                                                Cancel Booking
                                            </button>
                                        @elseif($cancellationExpired)
                                            <div class="space-y-2">
                                                <button type="button" disabled class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-500 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M10 14l2-2 2 2"></path>
                                                        <path d="M12 7v5"></path>
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                    </svg>
                                                    Cancellation unavailable
                                                </button>
                                                <p class="text-xs text-slate-500">The 5-minute cancellation timer has expired, so this booking can no longer be cancelled here.</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @if($cancellationRequested)
                                    @if(! $cancellationWindowActive)
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                            <p class="text-sm font-semibold text-amber-800">Cancellation</p>
                                            <p class="mt-2 text-sm text-amber-700">Enter where you'd like the refund sent. The 5-minute confirmation window begins when proof is uploaded.</p>
                                            <label class="mt-3 block">
                                                <span class="mb-2 block text-sm font-medium text-slate-700">Where should the agency send your refund?</span>
                                                <input
                                                    type="text"
                                                    wire:model.defer="refund_destination"
                                                    placeholder="e.g. GCash 0917xxxxxxx"
                                                    class="block w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2"
                                                    style="--tw-ring-color:#ee018d;"
                                                />
                                                @error('refund_destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <button wire:click.prevent="cancelCancellationRequest" type="button" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                    Cancel Request
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4" wire:poll.1s="tickCancelCountdown">
                                            <div class="flex items-center justify-between gap-2">
                                                <div>
                                                    <p class="text-sm font-semibold text-amber-800">Cancellation active</p>
                                                    <p class="mt-1 text-sm text-amber-700">You started the cancellation timer after uploading proof. Confirm within the next 5 minutes to cancel your booking.</p>
                                                </div>
                                                <span class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-amber-700">
                                                    {{ gmdate('i:s', max(0, $cancelCountdown)) }}
                                                </span>
                                            </div>
                                            <label class="mt-3 block">
                                                <span class="mb-2 block text-sm font-medium text-slate-700">Where should the agency send your refund?</span>
                                                <input
                                                    type="text"
                                                    wire:model.defer="refund_destination"
                                                    placeholder="e.g. GCash 0917xxxxxxx"
                                                    class="block w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2"
                                                    style="--tw-ring-color:#ee018d;"
                                                />
                                                @error('refund_destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <button wire:click.prevent="confirmCancellation" type="button" class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition" style="background:#ee018d;" onmouseover="this.style.background='#c30172'" onmouseout="this.style.background='#ee018d'">
                                                    Confirm Cancellation
                                                </button>
                                                <button wire:click.prevent="cancelCancellationRequest" type="button" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                    Cancel Request
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-center">
                            <p class="text-slate-700 font-medium">No booking found for "{{ $transaction_number }}".</p>
                            <p class="mt-1 text-sm text-slate-500">Double-check your transaction number and try again.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
