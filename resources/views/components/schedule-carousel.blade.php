@props([
    'title' => 'Select a schedule',
    'subtitle' => null,
    'origin' => null,
    'destination' => null,
    'schedules' => [],
    'selectedId' => null,
    'selectedAccommodationId' => null,
    'selectedClassId' => null,
    'selectMethod' => 'selectSchedule',
    'selectAccommodationMethod' => 'selectScheduleAccommodation',
    'selectClassMethod' => 'selectTransportClass',
    'mode' => 'ferry',
])

<div class="space-y-4">
    @if($subtitle)
        <p class="text-slate-500 text-sm mb-1">{{ $subtitle }}</p>
    @endif
    <h3 class="text-2xl font-bold text-[#5c1c85] mb-3">{{ $title }}</h3>
    @if($origin && $destination)
        <div class="bg-[#e0efff] text-[#5c1c85] font-semibold py-3 px-6 rounded-xl inline-flex items-center space-x-6 mb-4">
            <span>From {{ $origin }}</span>
            <span class="w-px h-5 bg-[#5c1c85]/30"></span>
            <span>To {{ $destination }}</span>
        </div>
    @endif
    
    @if(count($schedules) === 0)
        <p class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-600 text-sm">No schedules are available for this route on the selected date.</p>
    @else
        <!-- Alpine Carousel -->
        <div x-data="{ currentSlide: 0, slides: {{ count($schedules) }}, itemsPerSlide: 3 }" class="relative">
            <!-- Navigation -->
            <div class="absolute top-1/2 -left-4 -translate-y-1/2 z-10" x-show="currentSlide > 0">
                <button @click="currentSlide = Math.max(0, currentSlide - 1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow border border-slate-200 hover:bg-slate-50">
                    &lt;
                </button>
            </div>
            <div class="absolute top-1/2 -right-4 -translate-y-1/2 z-10" x-show="currentSlide < Math.ceil(slides / itemsPerSlide) - 1">
                <button @click="currentSlide = Math.min(Math.ceil(slides / itemsPerSlide) - 1, currentSlide + 1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow border border-slate-200 hover:bg-slate-50">
                    &gt;
                </button>
            </div>

            <!-- Slides Container -->
            <div class="overflow-hidden py-2 -mx-2 px-2">
                <div class="flex transition-transform duration-300" :style="'transform: translateX(calc(-' + (currentSlide * 100) + '%))'">
                    @foreach($schedules as $schedule)
                        <div class="w-1/3 flex-shrink-0 px-2">
                            <button type="button" wire:click.prevent="{{ $selectMethod }}({{ $schedule['id'] }})" class="w-full h-full rounded-2xl border p-4 text-left transition duration-200 flex flex-col {{ (int)$selectedId === (int)$schedule['id'] ? 'border-[#db2777] bg-[#db2777] text-white shadow-md' : 'border-slate-200 bg-white text-slate-900 hover:border-[#db2777]/50 hover:shadow-sm' }}">
                                <div class="flex items-start justify-between mb-2">
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
                                        <div class="w-10 h-10 shrink-0 bg-white rounded border {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/30 shadow' : 'border-slate-200' }} flex items-center justify-center p-1 overflow-hidden">
                                            <img src="{{ asset('images/' . $opLogo) }}" alt="{{ $opName }}" class="w-full h-full object-contain">
                                        </div>
                                    @endif
                                    <span class="rounded-full border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/30 bg-white/20 text-white' : 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $schedule['availability'] }}</span>
                                </div>
                                <h3 class="text-base font-bold">{{ $schedule['service'] }}</h3>
                                @if ($schedule['operator'])
                                    <p class="mt-1 text-xs font-medium {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white/80' : 'text-slate-600' }}">{{ $schedule['operator'] }}</p>
                                @endif
                                <p class="mt-2 text-xs font-semibold {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white' : 'text-slate-900' }}">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                <div class="mt-auto pt-3 border-t {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/20' : 'border-slate-100' }}">
                                    <p class="text-xs font-medium {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white/90' : 'text-slate-600' }}">Duration: {{ $schedule['duration'] }}</p>
                                    <p class="text-sm font-bold {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white' : 'text-slate-900' }}">Fare: ₱{{ number_format($schedule['price'], 2) }}</p>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Indicator Dots -->
            <div class="flex justify-center space-x-2 mt-4" x-show="slides > itemsPerSlide">
                <template x-for="i in Math.ceil(slides / itemsPerSlide)">
                    <button @click="currentSlide = i - 1" class="w-2 h-2 rounded-full transition-colors" :class="currentSlide === (i - 1) ? 'bg-[#db2777]' : 'bg-slate-300'"></button>
                </template>
            </div>
        </div>
    @endif

    @error($selectedId) <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror

    @if($selectedId)
        @php
            $selectedSchedule = collect($schedules)->firstWhere('id', $selectedId);
        @endphp

        {{-- Ferry: Show accommodations --}}
        @if($mode === 'ferry' && $selectedSchedule && !empty($selectedSchedule['accommodations']))
            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="text-slate-900 font-bold mb-3 text-sm">Select accommodation for this trip:</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($selectedSchedule['accommodations'] as $accommodation)
                        <button type="button" wire:click.prevent="{{ $selectAccommodationMethod }}({{ $accommodation['id'] }})" class="rounded-xl border-2 p-4 text-left transition duration-200 {{ (int)$selectedAccommodationId === (int)$accommodation['id'] ? 'border-[#db2777] bg-[#db2777]/5 shadow-sm' : 'border-slate-200 bg-white hover:border-[#db2777]/50 hover:shadow-sm' }}">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h4 class="font-bold text-slate-900 text-sm">{{ $accommodation['name'] }}</h4>
                                @if($accommodation['has_bed'])
                                    <span class="text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full border border-slate-200">With Bed</span>
                                @endif
                            </div>
                            <p class="mt-2 text-lg font-extrabold text-[#db2777]">₱{{ number_format($accommodation['price'], 2) }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Airline: Show transport classes --}}
        @if($mode === 'airline' && $selectedSchedule && !empty($selectedSchedule['transport_classes']))
            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="text-slate-900 font-bold mb-3 text-sm">Select travel class for this trip:</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($selectedSchedule['transport_classes'] as $class)
                        <button type="button" wire:click.prevent="{{ $selectClassMethod }}({{ $class['id'] }})" class="rounded-xl border-2 p-4 text-left transition duration-200 overflow-hidden {{ (int)$selectedClassId === (int)$class['id'] ? 'border-[#db2777] bg-[#db2777]/5 shadow-sm' : 'border-slate-200 bg-white hover:border-[#db2777]/50 hover:shadow-sm' }}">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $class['name'] }}</h4>
                            <p class="mt-2 text-lg font-extrabold text-[#db2777]">₱{{ number_format($class['price'], 2) }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
