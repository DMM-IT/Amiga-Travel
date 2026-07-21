<div class="flex items-center gap-1 rounded-xl bg-gray-100 p-1 dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
    <button 
        wire:click="setVehicleType('ferry')" 
        class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all {{ $action->getLivewire()->vehicleType === 'ferry' ? 'bg-white dark:bg-gray-800 shadow text-primary-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        Ferries
    </button>
    <button 
        wire:click="setVehicleType('airline')" 
        class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all {{ $action->getLivewire()->vehicleType === 'airline' ? 'bg-white dark:bg-gray-800 shadow text-primary-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        Airlines
    </button>
</div>
