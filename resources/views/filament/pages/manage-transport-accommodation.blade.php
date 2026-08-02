@php
    use Filament\Support\Enums\MaxWidth;
@endphp

<x-filament::page>
    <div class="space-y-6">
        <!-- Mode Toggle Switch -->
        <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Transport Type</h3>
                    <p class="text-sm text-gray-600">Switch between airline transport classes and ferry accommodations</p>
                </div>
                <div class="inline-grid grid-cols-2 gap-2 p-1 bg-gray-100 rounded-lg">
                    <button
                        type="button"
                        wire:click="switchMode('airline')"
                        class="px-4 py-2 rounded-md font-medium transition-colors {{ $mode === 'airline' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        ✈️ Airline
                    </button>
                    <button
                        type="button"
                        wire:click="switchMode('ferry')"
                        class="px-4 py-2 rounded-md font-medium transition-colors {{ $mode === 'ferry' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        ⛴️ Ferry
                    </button>
                </div>
            </div>
        </div>

        <!-- Ferry Operator Selector -->
        @if ($mode === 'ferry')
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                @if(count($ferryOperators) > 0)
                    <span class="block text-sm font-medium text-gray-900 mb-3">Select Ferry Operator</span>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($ferryOperators as $operator)
                            <button
                                type="button"
                                wire:click="updateOperator('{{ $operator }}')"
                                class="w-full rounded-lg px-4 py-3 text-center font-medium transition-colors border focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $selectedOperator === $operator ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                            >
                                {{ $operator }}
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-5 text-center text-sm text-gray-500">
                        No ferry operators are seeded yet.
                    </div>
                @endif
            </div>
        @endif

        <!-- Table Container -->
        <div class="rounded-lg bg-white shadow-sm border border-gray-200 overflow-hidden">
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>
