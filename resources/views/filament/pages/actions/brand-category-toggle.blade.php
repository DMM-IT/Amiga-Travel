@php
    $isCategory = request()->routeIs('filament.*.resources.vehicle-rates.*');
    $isBrand = request()->routeIs('filament.*.resources.vehicle-brands.*');
@endphp
<div class="flex items-center gap-1 rounded-xl bg-gray-100 p-1 dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
    <a 
        href="{{ \App\Filament\Resources\VehicleRateResource::getUrl('index') }}"
        class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all {{ $isCategory ? 'bg-white dark:bg-gray-800 shadow text-primary-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        Category
    </a>
    <a 
        href="{{ \App\Filament\Resources\VehicleBrandResource::getUrl('index') }}"
        class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all {{ $isBrand ? 'bg-white dark:bg-gray-800 shadow text-primary-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        Brand
    </a>
</div>
