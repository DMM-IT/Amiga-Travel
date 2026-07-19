<div class="relative inline-block w-full">
    @if(!empty($label))
        <label class="block text-emerald-700 font-medium">{{ $label }}</label>
    @endif
    <div class="mt-2 relative">
        @if($disabled)
            <button type="button" disabled class="w-full rounded-3xl border border-emerald-300 bg-emerald-100 px-4 py-3 text-left shadow-sm text-slate-500 transition">
                <span>{{ $value ?? 'Select date' }}</span>
            </button>
        @else
            <button type="button" wire:click="toggleCalendar" class="w-full rounded-3xl border border-emerald-300 bg-white px-4 py-3 text-left shadow-sm transition hover:border-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <span>{{ $value ?? 'Select date' }}</span>
                <span class="float-right text-emerald-600">▾</span>
            </button>
        @endif

        @if($isOpen)
            <div class="absolute left-0 right-0 mt-2 rounded-3xl border border-emerald-200 bg-white p-4 shadow-xl z-50">
                <div class="flex items-center justify-between text-emerald-900 font-semibold mb-3">
                    <button type="button" wire:click.prevent="prevMonth" class="rounded-full p-2 hover:bg-emerald-100">‹</button>
                    <div>{{ $this->monthLabel }} {{ $viewYear }}</div>
                    <button type="button" wire:click.prevent="nextMonth" class="rounded-full p-2 hover:bg-emerald-100">›</button>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center text-xs text-emerald-500">
                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $day)
                        <div>{{ $day }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1 mt-2 text-sm">
                    @foreach($this->calendarDays as $day)
                        @if($day === null)
                            <div class="h-10 rounded-2xl"></div>
                        @else
                            @php
                                $dayDate = sprintf('%04d-%02d-%02d', $viewYear, $viewMonth, $day['day']);
                                $isSelected = $value === $dayDate;
                            @endphp
                            <button
                                type="button"
                                wire:click.prevent="selectDate({{ $day['day'] }})"
                                @if($day['disabled']) disabled @endif
                                class="h-10 rounded-2xl transition {{ $isSelected ? 'bg-emerald-600 text-white' : ($day['disabled'] ? 'bg-slate-100 text-slate-400 cursor-not-allowed line-through shadow-none' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100') }}"
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
