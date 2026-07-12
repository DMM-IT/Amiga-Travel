@php
    use App\Models\WebsiteSetting;
    $pages = WebsiteSetting::PAGES;
    $currentPage = $this->currentPage ?? request('page', 'home');
@endphp

<x-filament-panels::page>
    <style>
        .page-selector {
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: var(--fi-page-background-color);
            border: 1px solid var(--fi-border-color);
        }
        
        .page-selector-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .page-selector-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 150ms ease-in-out;
            border: 1.5px solid var(--fi-border-color);
            text-decoration: none;
            display: inline-block;
        }
        
        .page-selector-btn.active {
            background-color: rgb(217, 119, 6);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-color: rgb(180, 83, 9);
        }
        
        .page-selector-btn.inactive {
            background-color: var(--fi-page-background-color);
            color: var(--fi-text-color);
            border-color: var(--fi-border-color);
        }
        
        .page-selector-btn.inactive:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-color: rgba(217, 119, 6, 0.5);
        }
    </style>
    
    <div class="mb-6">
        <div class="page-selector">
            <div class="page-selector-buttons">
                @foreach($pages as $pageKey => $pageLabel)
                    <a href="?page={{ $pageKey }}"
                       class="page-selector-btn {{ $currentPage === $pageKey ? 'active' : 'inactive' }}">
                        {{ $pageLabel }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-6">
        {{ $this->form }}

        <div class="flex gap-3 justify-end">
            <x-filament::button
                type="button"
                color="gray"
                tag="a"
                :href="route('filament.admin.pages.dashboard')">
                Cancel
            </x-filament::button>
            <x-filament::button
                type="button"
                wire:click="save">
                Save Settings
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
