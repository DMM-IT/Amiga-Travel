<div class="relative inline-block w-full">
    @if(!empty($label))
        <label class="block text-slate-700 font-semibold text-sm">{{ $label }}</label>
    @endif
    <div class="mt-2 relative">
        @if($disabled)
            <button type="button" disabled class="flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-left shadow-sm text-slate-500 transition cursor-not-allowed">
                <span>{{ $value ?? 'Select date' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
        @else
            <button type="button" wire:click="toggleCalendar" class="flex h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-slate-900 shadow-sm transition hover:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20">
                <span>{{ $value ?? 'Select date' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.045l3.71-3.815a.75.75 0 111.08 1.04l-4.25 4.375a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif

        @if($isOpen)
            <div class="absolute left-0 right-0 mt-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl z-50">
                <div class="flex items-center justify-between text-slate-900 font-bold mb-3">
                    <button type="button" wire:click.prevent="prevMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                    <div>{{ $this->monthLabel }} {{ $viewYear }}</div>
                    <button type="button" wire:click.prevent="nextMonth" class="rounded-full p-2 hover:bg-slate-100 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg></button>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-slate-500 mb-2">
                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $day)
                        <div>{{ $day }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1 text-sm">
                    @foreach($this->calendarDays as $day)
                        @if($day === null)
                            <div class="h-10 rounded-lg"></div>
                        @else
                            @php
                                $dayDate = sprintf('%04d-%02d-%02d', $viewYear, $viewMonth, $day['day']);
                                $isSelected = $value === $dayDate;
                            @endphp
                            <button
                                type="button"
                                wire:click.prevent="selectDate({{ $day['day'] }})"
                                @if($day['disabled']) disabled @endif
                                class="h-10 rounded-lg transition-colors font-medium flex items-center justify-center {{ $isSelected ? 'bg-[#216417] text-white shadow-md' : ($day['disabled'] ? 'bg-slate-50 text-slate-300 cursor-not-allowed line-through' : 'bg-white text-slate-700 hover:bg-slate-100 hover:text-slate-900') }}"
                            >
                                {{ $day['day'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
