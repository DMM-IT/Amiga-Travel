<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="bg-slate-900 px-6 py-8 sm:px-10">
                <h1 class="text-3xl font-semibold text-white">Amiga Gracia Travel Booking</h1>
                <p class="mt-2 text-slate-300 max-w-2xl">Complete your travel booking in a few easy steps. Your confirmation email and payment QR code are created automatically when you submit.</p>
            </div>

            <div class="p-6 sm:p-10">
                <div class="mb-8 grid gap-3 sm:grid-cols-6 text-sm">
                    @foreach(['Route','Schedule','Passengers','Discount','Stay','Submit'] as $index => $label)
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
                                <select wire:model.defer="origin" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                    <option value="">Select origin</option>
                                    @foreach($origins as $originOption)
                                        <option value="{{ $originOption }}">{{ $originOption }}</option>
                                    @endforeach
                                </select>
                                @error('origin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-slate-700 font-medium">Destination</span>
                                <select wire:model.defer="destination" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                    <option value="">Select destination</option>
                                    @foreach($this->destinations as $destinationOption)
                                        <option value="{{ $destinationOption }}">{{ $destinationOption }}</option>
                                    @endforeach
                                </select>
                                @error('destination')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </label>
                        </div>

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

                    @if ($step === 2)
                        <div class="space-y-4">
                            <p class="text-slate-600">Choose the ferry schedule that works best for your trip.</p>
                            <div class="grid gap-4 lg:grid-cols-3">
                                @forelse($availableSchedules as $schedule)
                                    <button type="button" wire:click.prevent="selectSchedule({{ $schedule['id'] }})" class="rounded-3xl border p-6 text-left transition duration-200 {{ $selected_schedule_id === $schedule['id'] ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-800 hover:border-slate-900' }}">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $schedule['service'] }}</h3>
                                                <p class="mt-1 text-sm text-slate-500">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                            </div>
                                            <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $selected_schedule_id === $schedule['id'] ? 'border-white bg-white/10 text-white' : 'border-slate-300 text-slate-500' }}">{{ $schedule['availability'] }}</span>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between">
                                            <p class="text-sm text-slate-500">Duration: {{ $schedule['duration'] }}</p>
                                            <p class="text-xl font-semibold">₱{{ number_format($schedule['price'], 2) }}</p>
                                        </div>
                                    </button>
                                @empty
                                    <p class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-slate-600 lg:col-span-3">No ferry schedules are available for this route on the selected date. Go back and try another date, or contact Amiga Gracia Travel Services.</p>
                                @endforelse
                            </div>
                            @error('selected_schedule_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
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
                            <p class="text-slate-600">Each traveler can have their own discount, if eligible. Name is optional.</p>

                            @php
                                $typeLabels = ['adult' => 'Adult', 'child' => 'Child', 'infant' => 'Infant'];
                                $countByType = [];
                            @endphp

                            @foreach($passengers as $index => $passenger)
                                @php
                                    $countByType[$passenger['type']] = ($countByType[$passenger['type']] ?? 0) + 1;
                                @endphp
                                <div wire:key="passenger-{{ $index }}" class="grid gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 lg:grid-cols-[auto_1fr_1fr] lg:items-end">
                                    <div class="rounded-full bg-slate-900 px-4 py-2 text-center text-sm font-semibold text-white lg:self-center">
                                        {{ $typeLabels[$passenger['type']] }} {{ $countByType[$passenger['type']] }}
                                    </div>

                                    <label class="block">
                                        <span class="text-slate-700 font-medium">Name (optional)</span>
                                        <input type="text" wire:model.defer="passengers.{{ $index }}.name" class="mt-2 block w-full rounded-3xl border border-slate-300 px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Traveler name" />
                                    </label>

                                    <label class="block">
                                        <span class="text-slate-700 font-medium">Discount</span>
                                        <select wire:model.defer="passengers.{{ $index }}.discount_id" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                            <option value="">No discount</option>
                                            @foreach($discounts as $discount)
                                                <option value="{{ $discount->id }}">{{ $discount->name }} ({{ $discount->percentage }}%)</option>
                                            @endforeach
                                        </select>
                                        @error('passengers.' . $index . '.discount_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($step === 5)
                        <div class="space-y-4">
                            <p class="text-slate-600">Pick any accommodations you'd like to add to your trip (optional).</p>

                            @if($accommodationCatalog->isEmpty())
                                <p class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-slate-600">No accommodations are available right now. You can continue without one.</p>
                            @else
                                <div class="grid gap-5 sm:grid-cols-2">
                                    @foreach($accommodationCatalog as $accommodation)
                                        @php $isSelected = isset($selected_accommodation_ids[$accommodation->id]); @endphp
                                        <button
                                            type="button"
                                            wire:key="accommodation-{{ $accommodation->id }}"
                                            wire:click.prevent="toggleAccommodation({{ $accommodation->id }})"
                                            class="text-left rounded-3xl border-2 overflow-hidden transition duration-200 {{ $isSelected ? 'border-slate-900 shadow-md' : 'border-slate-200 hover:border-slate-400' }}"
                                        >
                                            <div class="relative h-40 w-full bg-slate-200">
                                                @if($accommodation->cover_image)
                                                    <img src="{{ asset('storage/' . $accommodation->cover_image) }}" alt="{{ $accommodation->name }}" class="h-full w-full object-cover" />
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-slate-400 text-sm">No photo</div>
                                                @endif
                                                @if($isSelected)
                                                    <span class="absolute top-3 right-3 rounded-full bg-slate-900 text-white text-xs font-semibold px-3 py-1">Selected</span>
                                                @endif
                                            </div>
                                            <div class="p-4">
                                                <h3 class="font-semibold text-slate-900">{{ $accommodation->name }}</h3>
                                                @if($accommodation->description)
                                                    <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $accommodation->description }}</p>
                                                @endif
                                                <p class="mt-2 text-lg font-semibold text-slate-900">₱{{ number_format($accommodation->price, 2) }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
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
                                    @php
                                        $selectedAccommodations = $accommodationCatalog->filter(fn ($a) => isset($selected_accommodation_ids[$a->id]));
                                        $discountedCount = collect($passengers)->filter(fn ($p) => !empty($p['discount_id']))->count();
                                    @endphp
                                    <p class="text-slate-600"><span class="font-medium">Discounted travelers:</span> {{ $discountedCount }} of {{ count($passengers) }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Accommodations selected:</span> {{ $selectedAccommodations->count() }}</p>
                                    <p class="text-slate-600"><span class="font-medium">Estimated total:</span> ₱{{ number_format($this->calculateTotalPrice(), 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                @forelse($passengers as $passenger)
                                    <div class="rounded-2xl bg-white p-4 border border-slate-200 flex items-center justify-between">
                                        <span class="text-slate-800">{{ ucfirst($passenger['type']) }}{{ $passenger['name'] ? ' — ' . $passenger['name'] : '' }}</span>
                                        <span class="text-slate-600 text-sm">{{ optional($discounts->firstWhere('id', $passenger['discount_id']))->name ?? 'No discount' }}</span>
                                    </div>
                                @empty
                                    <p class="text-slate-500">No passengers added yet.</p>
                                @endforelse
                            </div>

                            <div class="mt-6 space-y-3">
                                @forelse($selectedAccommodations as $accommodation)
                                    <div class="rounded-2xl bg-white p-4 border border-slate-200">
                                        <p class="text-slate-800 font-medium">{{ $accommodation->name }}</p>
                                        <p class="text-slate-600">Price: ₱{{ number_format($accommodation->price, 2) }}</p>
                                    </div>
                                @empty
                                    <p class="text-slate-500">No accommodations selected.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <h2 class="text-lg font-semibold text-slate-900">Selected schedule</h2>
                            @php
                                $schedule = collect($availableSchedules)->firstWhere('id', $selected_schedule_id);
                            @endphp
                            @if ($schedule)
                                <div class="mt-4 rounded-3xl bg-white p-4 border border-slate-200">
                                    <p class="text-slate-800 font-semibold">{{ $schedule['service'] }}</p>
                                    <p class="text-slate-600">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                    <p class="text-slate-600">Duration: {{ $schedule['duration'] }}</p>
                                    <p class="text-slate-600">Price: ₱{{ number_format($schedule['price'], 2) }}</p>
                                </div>
                            @else
                                <p class="mt-4 text-slate-600">No schedule selected yet.</p>
                            @endif
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
                        @endif

                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                            @if ($step > 1)
                                <button type="button" wire:click.prevent="previousStep" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Back</button>
                            @endif

                            @if ($step < 6)
                                <button type="button" wire:click.prevent="nextStep" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">Next</button>
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
