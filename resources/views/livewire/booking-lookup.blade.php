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
                                            Done
                                        </a>

                                        @if($booking->canCancelOrRebook())
                                            @if(! $cancellationRequested && ! $cancellationExpired && ! $rebookingRequested)
                                                <button wire:click.prevent="requestCancellation" type="button" class="inline-flex items-center justify-center rounded-3xl border border-pink-500 px-6 py-3 text-sm font-semibold text-pink-700 transition hover:bg-pink-50">
                                                    Cancel Booking
                                                </button>
                                                <button wire:click.prevent="requestRebooking" type="button" class="inline-flex items-center justify-center rounded-3xl border border-blue-500 px-6 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                                                    Rebook
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
                                        @else
                                            <div class="space-y-2">
                                                <button type="button" disabled class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-500 shadow-sm">
                                                    Actions Unavailable
                                                </button>
                                                <p class="text-xs text-slate-500">You cannot cancel or rebook this booking as the departure date has passed.</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @if($cancellationRequested)
                                    @if(! $cancellationWindowActive)
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                            <p class="text-sm font-semibold text-amber-800">Cancellation</p>
                                            <p class="mt-2 text-sm text-amber-700">Enter where you'd like the refund sent. Cancellation fee: 50% of total price (₱{{ number_format($booking->getCancellationFeeAmount(), 2) }}), Refund amount: 50% (₱{{ number_format($booking->getRefundAmount(), 2) }}).</p>
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
                                                <button wire:click.prevent="requestRebooking" type="button" class="inline-flex items-center justify-center rounded-3xl border border-blue-500 px-6 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                                                    Switch to Rebook
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4" wire:poll.1s="tickCancelCountdown">
                                            <div class="flex items-center justify-between gap-2">
                                                <div>
                                                    <p class="text-sm font-semibold text-amber-800">Cancellation active</p>
                                                    <p class="mt-1 text-sm text-amber-700">You started the cancellation timer after uploading proof. Confirm within the next 5 minutes to cancel your booking. Refund is 50% of total price.</p>
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
                                                <button wire:click.prevent="requestRebooking" type="button" class="inline-flex items-center justify-center rounded-3xl border border-blue-500 px-6 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                                                    Switch to Rebook
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if($rebookingRequested && ! $rebookingPaid)
                                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                                        <p class="text-sm font-semibold text-blue-800">Rebooking</p>
                                        <p class="mt-2 text-sm text-blue-700">To rebook, please select your new travel dates and upload proof of payment for the 30% rebooking fee: ₱{{ number_format($booking->getRebookingFeeAmount(), 2) }}.</p>

                                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                                <p class="text-sm font-medium text-slate-700">Trip Type</p>
                                                <p class="mt-2 text-base font-semibold text-slate-900">{{ $booking->return_date ? 'Round trip' : 'One-way' }}</p>
                                            </div>
                                            <label class="block">
                                                <span class="mb-2 block text-sm font-medium text-slate-700">Departure Date</span>
                                                <input type="date" wire:model="rebooking_departure_date" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#3b82f6;" />
                                                @error('rebooking_departure_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                        </div>

                                        @if($booking->return_date)
                                            <label class="mt-4 block">
                                                <span class="mb-2 block text-sm font-medium text-slate-700">Return Date</span>
                                                <input type="date" wire:model="rebooking_return_date" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2" style="--tw-ring-color:#3b82f6;" />
                                                @error('rebooking_return_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </label>
                                        @endif

                                        @php $rebookingQrPath = 
                                            App\Models\PaymentSetting::current()->qr_code_path ?? null;
                                        @endphp
                                        @if($rebookingQrPath)
                                            <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                                    <div class="flex-shrink-0 bg-slate-50 p-3 rounded-3xl border border-slate-200">
                                                        <img src="{{ asset('storage/' . $rebookingQrPath) }}" alt="Rebooking payment QR code" class="h-32 w-32 rounded-2xl object-contain" />
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-slate-900">Scan to pay the rebooking fee</p>
                                                        <p class="mt-2 text-sm text-slate-600">Use your preferred mobile wallet to scan this QR code and pay the exact rebooking fee: <span class="font-semibold">₱{{ number_format($booking->getRebookingFeeAmount(), 2) }}</span>.</p>
                                                        <p class="mt-3 text-xs text-slate-500">After payment, upload a photo of the receipt or payment confirmation below.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <label class="mt-4 block">
                                            <span class="mb-2 block text-sm font-medium text-slate-700">Proof of Rebooking Fee Payment</span>
                                            <input type="file" wire:model="rebookingProof" class="mt-2 block w-full text-sm text-slate-600" />
                                            @error('rebookingProof')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </label>

                                        <div class="mt-4 flex flex-wrap gap-3">
                                            <button 
                                                type="button" 
                                                wire:click.prevent="submitRebookingProof" 
                                                class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition"
                                                style="background:#3b82f6;"
                                                onmouseover="this.style.background='#2563eb'"
                                                onmouseout="this.style.background='#3b82f6'"
                                                @disabled($isUploadingRebooking || !$rebookingProof)
                                            >
                                                @if($isUploadingRebooking)
                                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Uploading...
                                                @else
                                                    Upload & Confirm Rebooking
                                                @endif
                                            </button>
                                            <button wire:click.prevent="$set('rebookingRequested', false); $set('feedback', null)" type="button" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if($rebookingPaid)
                                    <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                                        <p class="text-sm font-semibold text-green-800">Rebooking Fee Paid!</p>
                                        <p class="mt-2 text-sm text-green-700">Your rebooking fee payment has been received. Please contact us to complete your rebooking.</p>
                                    </div>
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
