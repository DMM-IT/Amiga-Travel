@php
    use App\Models\WebsiteSetting;
    $pages = WebsiteSetting::PAGES;
    $currentPage = $this->currentPage ?? request('page', 'home');

    $settings = $this->settingsData ?? [];
    $content = $settings['content'] ?? [];
    $heroImages = $settings['hero_images'] ?? [];
    $bookingCards = $settings['booking_cards'] ?? [];
    $headerData = $settings['header_data'] ?? [];
    $footerData = $settings['footer_data'] ?? [];

    $imageUrl = fn (?string $path): ?string => $path ? (str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'))) : null;
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

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
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

        <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">Mock Website Preview</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Preview how the selected page will appear.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ $pages[$currentPage] ?? 'Page' }}</span>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-slate-50 p-4 dark:border-gray-700 dark:bg-gray-950">
                @php
                    $heroImage = $heroImages[0] ?? data_get($content, 'hero_image');
                    $pageBadge = data_get($content, 'badge') ?? ($pages[$currentPage] ?? 'Page');
                    $pageTitle = data_get($content, 'title') ?? ($pages[$currentPage] ?? 'Page title');
                    $pageDescription = data_get($content, 'description') ?? 'No page description has been configured yet.';
                @endphp

                <div class="rounded-3xl overflow-hidden bg-white shadow-sm dark:bg-gray-900 dark:ring-1 dark:ring-gray-700">
                    @if ($heroImage)
                        <img src="{{ $imageUrl($heroImage) }}" alt="Page hero image" class="h-80 w-full object-cover" />
                    @else
                        <div class="flex h-80 items-center justify-center bg-slate-100 text-slate-400 dark:bg-gray-950 dark:text-slate-500">
                            No hero image selected
                        </div>
                    @endif
                </div>

                <div class="mt-6 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ $pageBadge }}</span>
                    <h3 class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $pageTitle }}</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">{{ $pageDescription }}</p>

                    @if ($currentPage === 'about')
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach(data_get($content, 'quick_facts', []) as $fact)
                                <div class="rounded-3xl border border-slate-200 bg-emerald-50 p-4 text-sm text-slate-900 dark:border-gray-700 dark:bg-emerald-950/20 dark:text-white">
                                    <div class="font-semibold">{{ data_get($fact, 'label') }}</div>
                                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-300">{{ data_get($fact, 'value') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'gallery')
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach(array_slice(data_get($content, 'gallery_items', []), 0, 4) as $item)
                                <div class="rounded-3xl overflow-hidden border border-slate-200 bg-slate-50 shadow-sm">
                                    @if(data_get($item, 'image'))
                                        <img src="{{ $imageUrl(data_get($item, 'image')) }}" alt="{{ data_get($item, 'alt') }}" class="h-40 w-full object-cover" />
                                    @else
                                        <div class="h-40 bg-slate-100 flex items-center justify-center text-slate-400">No image</div>
                                    @endif
                                    <div class="p-4">
                                        <div class="text-xs uppercase tracking-[0.2em] text-emerald-700">{{ data_get($item, 'label') }}</div>
                                        <div class="mt-2 font-semibold text-slate-900">{{ data_get($item, 'title') }}</div>
                                        <p class="mt-1 text-xs text-slate-500">{{ data_get($item, 'description') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'services')
                        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div class="font-semibold text-slate-900">Service CTA</div>
                            <p class="mt-2 text-sm text-slate-600">{{ data_get($content, 'service_cta.description') }}</p>
                            <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-emerald-700 border border-emerald-100">{{ data_get($content, 'service_cta.button_text') }}</div>
                        </div>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            @foreach(array_slice(data_get($content, 'service_cards', []), 0, 4) as $card)
                                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="font-semibold text-slate-900">{{ data_get($card, 'title') }}</div>
                                    <p class="mt-2 text-xs text-slate-500">{{ data_get($card, 'description') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'tour_package')
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-emerald-50 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-emerald-700">Domestic Packages</div>
                                <div class="mt-2 font-semibold text-slate-900">{{ count(data_get($content, 'tour_packages.domestic', [])) }} items</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-pink-50 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-pink-700">International Packages</div>
                                <div class="mt-2 font-semibold text-slate-900">{{ count(data_get($content, 'tour_packages.international', [])) }} items</div>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach(array_slice(data_get($content, 'supported_destinations', []), 0, 2) as $group)
                                <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                    <div class="font-semibold text-slate-900">{{ data_get($group, 'title') }}</div>
                                    <ul class="mt-2 text-xs text-slate-500 space-y-1">
                                        @foreach(data_get($group, 'destinations', []) as $destination)
                                            <li>{{ $destination }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'download')
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-emerald-50 p-4">
                                <div class="font-semibold text-slate-900">{{ data_get($content, 'how_it_works_label', 'How It Works') }}</div>
                                <div class="mt-2 text-xs text-slate-600">{{ data_get($content, 'how_it_works_description') }}</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                <div class="font-semibold text-slate-900">{{ data_get($content, 'how_it_works_title', 'Install in 3 Easy Steps') }}</div>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach(array_slice(data_get($content, 'download_steps', []), 0, 4) as $step)
                                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Step {{ data_get($step, 'number') ?? '•' }}</div>
                                    <div class="mt-2 font-semibold text-slate-900">{{ data_get($step, 'title') }}</div>
                                    <p class="mt-1 text-xs text-slate-500">{{ data_get($step, 'description') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'contact_us')
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                <div class="font-semibold text-slate-900">Phone</div>
                                <p class="mt-2 text-xs text-slate-500">{{ data_get($content, 'phone', 'Not set') }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                <div class="font-semibold text-slate-900">Email</div>
                                <p class="mt-2 text-xs text-slate-500">{{ data_get($content, 'email', 'Not set') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div class="font-semibold text-slate-900">Address</div>
                            <p class="mt-2 text-xs text-slate-500">{!! nl2br(e(data_get($content, 'address', 'Not available'))) !!}</p>
                        </div>
                    @else
                        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            This preview shows the primary hero and content for the selected page.
                        </div>
                    @endif
                </div>

                @if ($currentPage === 'home' && count($bookingCards))
                    <div class="mt-6 space-y-3">
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">Booking cards</div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($bookingCards as $card)
                                <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                                    <div class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">Booking</div>
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $card['title'] ?? 'Card title' }}</div>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $card['description'] ?? 'Card description goes here.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-5 rounded-3xl border border-dashed border-gray-200 bg-white/80 p-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-white/5 dark:text-gray-300">
                    <div class="font-semibold text-gray-800 dark:text-white">Page data</div>
                    <div class="mt-3 grid gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <div><strong>Subtitle:</strong> {{ $content['page_subtitle'] ?? 'No subtitle set' }}</div>
                        <div><strong>Meta title:</strong> {{ $content['meta_title'] ?? 'Not configured' }}</div>
                        <div><strong>Meta description:</strong> {{ $content['meta_description'] ?? 'Not configured' }}</div>
                        <div><strong>Status:</strong> {{ $settings['is_active'] ?? true ? 'Active' : 'Inactive' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
