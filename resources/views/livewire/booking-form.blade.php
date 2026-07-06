@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="bg-slate-900 px-6 py-8 sm:px-10">
                <h1 class="text-3xl font-semibold text-white">Amiga Gracia Travel Booking</h1>
                <p class="mt-2 text-slate-300 max-w-2xl">Complete your travel booking in a few easy steps. Your confirmation email and payment QR code are created automatically when you submit.</p>
            </div>

            <div class="p-6 sm:p-10">
                <div class="mb-8 grid gap-3 sm:grid-cols-6 text-sm">
                    @foreach(['Route','Dates','Passengers','Discount','Stay','Submit'] as $index => $label)
                        <div class="rounded-2xl border px-3 py-2 text-center transition-all duration-200 {{ $step === $index + 1 ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                            <div class="font-semibold">{{ $index + 1 }}</div>
                            <div>{{ $label }}</div>
                        </div>
                    @endforeach
                </div>

                <form wire:submit.prevent="submit" class="space-y-8">
                    @if ($step === 1)
                        <div class="grid gap-6 lg:grid-cols-2">
                            <label class="block">
                                <span class="text-slate-700 font-medium">Origin</span>
                                <input type="text" wire:model.defer="origin" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="e.g. Manila" />
                                @error('origin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Destination</span>
                                <input type="text" wire:model.defer="destination" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="e.g. Boracay" />
                                @error('destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    @endif

                    @if ($step === 2)
                        <div class="grid gap-6 lg:grid-cols-2">
                            <label class="block">
                                <span class="text-slate-700 font-medium">Departure Date</span>
                                <input type="date" wire:model.defer="departure_date" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                @error('departure_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Return Date (optional)</span>
                                <input type="date" wire:model.defer="return_date" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                @error('return_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    @endif

                    @if ($step === 3)
                        <div class="grid gap-6 lg:grid-cols-3">
                            <label class="block">
                                <span class="text-slate-700 font-medium">Adults</span>
                                <input type="number" min="1" wire:model.defer="adults" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                @error('adults')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Children</span>
                                <input type="number" min="0" wire:model.defer="children" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                @error('children')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Infants</span>
                                <input type="number" min="0" wire:model.defer="infants" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                @error('infants')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    @endif

                    @if ($step === 4)
                        <div class="space-y-4">
                            <label class="block">
                                <span class="text-slate-700 font-medium">Choose a discount</span>
                                <select wire:model.defer="selected_discount_id" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                    <option value="">No discount</option>
                                    @foreach($discounts as $discount)
                                        <option value="{{ $discount->id }}">{{ $discount->name }} ({{ $discount->percentage }}%)</option>
                                    @endforeach
                                </select>
                                @error('selected_discount_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <p class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-slate-600">Use discounts for eligible travelers like students and seniors. The selected discount is stored with the passenger records.</p>
                        </div>
                    @endif

                    @if ($step === 5)
                        <div class="space-y-4">
                            @foreach($accommodations as $index => $accommodation)
                                <div wire:key="accommodation-{{ $index }}" class="grid gap-4 lg:grid-cols-[1fr_180px_auto] items-end">
                                    <label class="block">
                                        <span class="text-slate-700 font-medium">Accommodation</span>
                                        <input type="text" wire:model.defer="accommodations.{{ $index }}.name" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Resort / Hotel name" />
                                        @error('accommodations.' . $index . '.name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </label>

                                    <label class="block">
                                        <span class="text-slate-700 font-medium">Price</span>
                                        <input type="number" min="0" step="0.01" wire:model.defer="accommodations.{{ $index }}.price" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="0.00" />
                                        @error('accommodations.' . $index . '.price')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </label>

                                    <button type="button" wire:click.prevent="removeAccommodation({{ $index }})" class="rounded-full border border-slate-300 bg-white px-4 py-3 text-slate-600 transition hover:border-rose-500 hover:text-rose-600">Remove</button>
                                </div>
                            @endforeach

                            <button type="button" wire:click.prevent="addAccommodation" class="inline-flex items-center gap-2 rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">Add accommodation</button>
                        </div>
                    @endif

                    @if ($step === 6)
                        <div class="grid gap-6 lg:grid-cols-2">
                            <label class="block">
                                <span class="text-slate-700 font-medium">Your name</span>
                                <input type="text" wire:model.defer="client_name" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Jane Doe" />
                                @error('client_name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Email address</span>
                                <input type="email" wire:model.defer="client_email" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="you@example.com" />
                                @error('client_email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <h2 class="text-lg font-semibold text-slate-900">Review</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <p class="text-slate-600"><span class="font-medium">Route:</span> {{ $origin }} → {{ $destination }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Dates:</span> {{ $departure_date }}{{ $return_date ? ' → ' . $return_date : '' }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Passengers:</span> {{ $adults }} adults, {{ $children }} children, {{ $infants }} infants</p>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-slate-600"><span class="font-medium">Discount:</span> {{ optional($discounts->firstWhere('id', $selected_discount_id))->name ?? 'None' }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Total accommodations:</span> {{ count($accommodations) }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Estimated total:</span> ₱{{ number_format($this->calculateTotalPrice(), 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @foreach($accommodations as $accommodation)
                                    <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                        <p class="text-slate-800 font-medium">{{ $accommodation['name'] ?: 'Unnamed accommodation' }}</p>
                                        <p class="text-slate-600">Price: ₱{{ number_format(floatval($accommodation['price'] ?? 0), 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div wire:ignore class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
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

                        @if ($step < 6)
                            <button type="button" wire:click.prevent="nextStep" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">Next</button>
                        @else
                            <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">Submit Booking</button>
                        @endif
                    @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
