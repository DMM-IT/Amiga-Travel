@props([
    'schedule' => [],
    'selectedId' => null,
    'selectMethod' => 'selectSchedule',
])
@php
    $isSelected = (int)$selectedId === (int)($schedule['id'] ?? -1);
    $opName = $schedule['operator'] ?? '';
    $opLogo = null;
    if (stripos($opName, '2GO') !== false) $opLogo = '2GO-Logo.png';
    elseif (stripos($opName, 'Starlite') !== false) $opLogo = 'starlite-Logo.jfif';
    elseif (stripos($opName, 'Cebu') !== false) $opLogo = 'CebuPecific-Logo.png';
    elseif (stripos($opName, 'Pal') !== false || stripos($opName, 'Philippine Airlines') !== false) $opLogo = 'Pal-Logo.jfif';
    elseif (stripos($opName, 'AirAsia') !== false) $opLogo = 'AirAsia-Logo.png';
@endphp
<button
    type="button"
    wire:click.prevent="{{ $selectMethod }}({{ $schedule['id'] }})"
    class="w-full h-full min-h-[168px] sm:min-h-[190px] rounded-xl sm:rounded-2xl border p-3 sm:p-4 text-left transition duration-200 flex flex-col {{ $isSelected
        ? 'border-[#db2777] bg-[#db2777] text-white shadow-md ring-2 ring-[#db2777]/20 ring-offset-2 ring-offset-slate-50'
        : 'border-slate-200 bg-white text-slate-900 hover:border-[#db2777]/50 hover:shadow-sm'
    }}"
>
    <div class="flex items-start justify-between mb-2 gap-1">
        @if($opLogo)
            <div class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 bg-white rounded border {{ $isSelected ? 'border-white/30 shadow' : 'border-slate-200' }} flex items-center justify-center p-1 overflow-hidden">
                <img src="{{ asset('images/' . $opLogo) }}" alt="{{ $opName }}" class="w-full h-full object-contain">
            </div>
        @else
            <div class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 rounded border {{ $isSelected ? 'border-white/30 bg-white/20' : 'border-slate-200 bg-slate-50' }} flex items-center justify-center">
                @if(isset($schedule['type']) && $schedule['type'] === 'airline')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 {{ $isSelected ? 'text-white' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M3.131 12.517L21.75 5.25a.75.75 0 01.969.97l-7.267 18.619a.75.75 0 01-1.438-.243l-1.952-7.562a.75.75 0 00-.505-.505l-7.562-1.952a.75.75 0 01-.243-1.438l-1.672-1.672zm12.44-8.767l-8.61 8.61 3.173.82a2.25 2.25 0 011.516 1.516l.82 3.173 8.61-8.61-5.509-5.509z" clip-rule="evenodd"/></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 {{ $isSelected ? 'text-white' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M3.5 10.5a2.5 2.5 0 012.5-2.5h12a2.5 2.5 0 012.5 2.5v5a2.5 2.5 0 01-2.5 2.5h-12a2.5 2.5 0 01-2.5-2.5v-5zM5 14a1 1 0 100-2 1 1 0 000 2zm10 1a1 1 0 11-2 0 1 1 0 012 0zM19 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                @endif
            </div>
        @endif
        <span class="rounded-full border px-1.5 sm:px-2 py-0.5 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider ml-auto {{ $isSelected ? 'border-white/30 bg-white/20 text-white' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
            {{ $schedule['availability'] ?? 'Available' }}
        </span>
    </div>

    <h3 class="text-sm sm:text-base font-bold leading-tight line-clamp-1">
        {{ $schedule['service'] ?? '' }}
    </h3>
    @if (!empty($schedule['operator']))
        <p class="mt-0.5 text-[10px] sm:text-xs font-medium truncate {{ $isSelected ? 'text-white/80' : 'text-slate-600' }}">
            {{ $schedule['operator'] }}
        </p>
    @endif
    <p class="mt-1.5 text-[10px] sm:text-xs font-semibold {{ $isSelected ? 'text-white' : 'text-slate-900' }}">
        {{ $schedule['departure'] ?? '' }} &rarr; {{ $schedule['arrival'] ?? '' }}
    </p>

    <div class="mt-auto pt-2 sm:pt-3 border-t {{ $isSelected ? 'border-white/20' : 'border-slate-100' }}">
        <p class="text-[10px] sm:text-xs font-medium {{ $isSelected ? 'text-white/90' : 'text-slate-600' }}">
            {{ $schedule['duration'] ?? '' }}
        </p>
        <p class="text-xs sm:text-sm font-bold {{ $isSelected ? 'text-white' : 'text-slate-900' }}">
            &#8369;{{ number_format($schedule['price'] ?? 0, 2) }}
        </p>
    </div>
</button>
