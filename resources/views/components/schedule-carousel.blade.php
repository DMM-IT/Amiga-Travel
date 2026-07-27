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
        <!-- Alpine Carousel: 2 cards on mobile, 3 on sm+ -->
        <div
            x-data="{
                currentSlide: 0,
                slides: {{ count($schedules) }},
                itemsPerSlide: window.innerWidth >= 640 ? 3 : 2,
                init() {
                    const update = () => {
                        const newItems = window.innerWidth >= 640 ? 3 : 2;
                        if (newItems !== this.itemsPerSlide) {
                            this.itemsPerSlide = newItems;
                            this.currentSlide = 0;
                        }
                    };
                    window.addEventListener('resize', update);
                }
            }"
            class="relative group"
        >
            <!-- Navigation -->
            <button x-show="currentSlide > 0" @click="currentSlide = Math.max(0, currentSlide - 1)" class="absolute -left-3 sm:-left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.15)] border border-slate-100 flex items-center justify-center text-slate-600 hover:text-[#216417] hover:border-[#216417] transition-all sm:opacity-0 sm:group-hover:opacity-100">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 pr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            
            <button x-show="currentSlide < Math.ceil(slides / itemsPerSlide) - 1" @click="currentSlide = Math.min(Math.ceil(slides / itemsPerSlide) - 1, currentSlide + 1)" class="absolute -right-3 sm:-right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.15)] border border-slate-100 flex items-center justify-center text-slate-600 hover:text-[#216417] hover:border-[#216417] transition-all sm:opacity-0 sm:group-hover:opacity-100">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 pl-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Slides Container -->
            <div class="overflow-hidden py-2 -mx-2 px-2">
                <div class="flex transition-transform duration-300" :style="'transform: translateX(calc(-' + (currentSlide * 100) + '%))'" >
                    @foreach($schedules as $schedule)
                        {{-- w-1/2 on mobile (2 per slide), w-1/3 on sm+ (3 per slide) --}}
                        <div class="w-1/2 sm:w-1/3 flex-shrink-0 px-1.5 sm:px-2">
                            <button type="button" wire:click.prevent="{{ $selectMethod }}({{ $schedule['id'] }})" class="w-full h-full rounded-xl sm:rounded-2xl border p-3 sm:p-4 text-left transition duration-200 flex flex-col {{ (int)$selectedId === (int)$schedule['id'] ? 'border-[#db2777] bg-[#db2777] text-white shadow-md' : 'border-slate-200 bg-white text-slate-900 hover:border-[#db2777]/50 hover:shadow-sm' }}">
                                <div class="flex items-start justify-between mb-2 gap-1">
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
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 bg-white rounded border {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/30 shadow' : 'border-slate-200' }} flex items-center justify-center p-1 overflow-hidden">
                                            <img src="{{ asset('images/' . $opLogo) }}" alt="{{ $opName }}" class="w-full h-full object-contain">
                                        </div>
                                    @endif
                                    <span class="rounded-full border px-1.5 sm:px-2 py-0.5 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider ml-auto {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/30 bg-white/20 text-white' : 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $schedule['availability'] }}</span>
                                </div>
                                <h3 class="text-sm sm:text-base font-bold leading-tight">{{ $schedule['service'] }}</h3>
                                @if ($schedule['operator'])
                                    <p class="mt-0.5 text-[10px] sm:text-xs font-medium {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white/80' : 'text-slate-600' }}">{{ $schedule['operator'] }}</p>
                                @endif
                                <p class="mt-1.5 text-[10px] sm:text-xs font-semibold {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white' : 'text-slate-900' }}">{{ $schedule['departure'] }} → {{ $schedule['arrival'] }}</p>
                                <div class="mt-auto pt-2 sm:pt-3 border-t {{ (int)$selectedId === (int)$schedule['id'] ? 'border-white/20' : 'border-slate-100' }}">
                                    <p class="text-[10px] sm:text-xs font-medium {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white/90' : 'text-slate-600' }}">{{ $schedule['duration'] }}</p>
                                    <p class="text-xs sm:text-sm font-bold {{ (int)$selectedId === (int)$schedule['id'] ? 'text-white' : 'text-slate-900' }}">₱{{ number_format($schedule['price'], 2) }}</p>
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
