@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'domestic' }">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">{{ $pageContent['badge'] ?? 'Tour Packages' }}</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">{{ $pageContent['title'] ?? 'Explore Our Packages' }}</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $pageContent['description'] ?? 'Affordable, reliable, and handpicked local and international tour arrangements designed for unforgettable travel memories.' }}
            </p>

        @php
            $tourPackages = $pageContent['tour_packages'] ?? [
                'domestic' => [
                    [
                        'image' => 'https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'El Nido',
                        'label' => 'Best Seller',
                        'title' => 'El Nido Adventure',
                        'subtitle' => '3 Days & 2 Nights · Inclusions: Flight + Hotel + Island Tour',
                        'description' => 'Discover limestone cliffs, crystal clear lagoons, and pristine beaches of Bacuit Bay. Includes a guided Island Tour A.',
                        'price' => '₱18,499',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'Boracay',
                        'label' => 'Popular',
                        'title' => 'Boracay Island Escape',
                        'subtitle' => '3 Days & 2 Nights · Inclusions: Flight + Hotel + Transfers',
                        'description' => 'Relax on the world-famous white sand beach. Enjoy sunset paraw sailing, vibrant island nightlife, and local water sports.',
                        'price' => '₱7,499',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'Siargao',
                        'label' => 'Trending',
                        'title' => 'Siargao Surf & Island Tour',
                        'subtitle' => '4 Days & 3 Nights · Inclusions: Hotel + Island Hopping + Surf Lesson',
                        'description' => 'Discover the surfing capital. Tour Guyam, Daku, and Naked island, followed by a professional beginner surf lesson at Cloud 9.',
                        'price' => '₱9,299',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                ],
                'international' => [
                    [
                        'image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'Bangkok',
                        'label' => 'Fly to Bangkok',
                        'title' => 'Bangkok & Pattaya Highlights',
                        'subtitle' => '4 Days & 3 Nights · Inclusions: Flight + 4★ Hotel + City Tour',
                        'description' => 'Experience majestic Buddhist temples, vibrant street food markets, and the beach resorts of Pattaya. Includes Grand Palace tour.',
                        'price' => '₱22,999',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1538669715516-b2a59a7ef249?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'Seoul',
                        'label' => 'K-Culture Tour',
                        'title' => 'Seoul & Nami Island Experience',
                        'subtitle' => '5 Days & 4 Nights · Inclusions: Flight + Hotel + Visa Assist',
                        'description' => 'Explore Gyeongbokgung Palace in traditional Hanbok clothing. Cruise to scenic Nami Island and shop in Myeongdong district.',
                        'price' => '₱24,999',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=600&q=80',
                        'alt' => 'Kyoto',
                        'label' => 'Cherry Blossom',
                        'title' => 'Tokyo, Kyoto & Osaka Classic',
                        'subtitle' => '6 Days & 5 Nights · Inclusions: Flight + Bullet Train + Hotel',
                        'description' => 'Witness the futuristic Tokyo streets, take the Shinkansen bullet train to historic Kyoto shrines, and enjoy street food in Dotonbori, Osaka.',
                        'price' => '₱38,999',
                        'button_text' => 'Book Now',
                        'button_link' => url('/book/new'),
                    ],
                ],
            ];
            $supportedDestinations = $pageContent['supported_destinations'] ?? [
                [
                    'title' => 'Southeast Asia',
                    'destinations' => [
                        'Thailand (Bangkok)',
                        'Vietnam (Hanoi/HCMC)',
                        'Singapore',
                        'Indonesia (Bali)',
                    ],
                ],
                [
                    'title' => 'East Asia',
                    'destinations' => [
                        'South Korea (Seoul)',
                        'Japan (Tokyo/Osaka)',
                        'Taiwan (Taipei)',
                        'China (Shanghai)',
                        'Hong Kong',
                    ],
                ],
                [
                    'title' => 'Philippine Beaches',
                    'destinations' => [
                        'Puerto Galera',
                        'Boracay Island',
                        'El Nido, Palawan',
                        'Siargao Island',
                    ],
                ],
                [
                    'title' => 'Philippine Cities',
                    'destinations' => [
                        'Cebu City',
                        'Bohol (Tagbilaran)',
                        'Manila Metro',
                        'Davao City',
                    ],
                ],
            ];
        @endphp

            <!-- Interactive Tab Buttons -->
            <div class="mt-10 inline-flex p-1 bg-slate-200/80 rounded-2xl">
                <button @click="activeTab = 'domestic'" 
                        :class="activeTab === 'domestic' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-2.5 rounded-xl font-bold text-sm transition cursor-pointer">
                    Domestic Packages
                </button>
                <button @click="activeTab = 'international'" 
                        :class="activeTab === 'international' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-2.5 rounded-xl font-bold text-sm transition cursor-pointer">
                    International Packages
                </button>
            </div>
        </div>

        <!-- Domestic Packages Tab -->
        <div id="domestic-packages" x-show="activeTab === 'domestic'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Server-side fallback packages (will be replaced by client-side JS when available) --}}
            @foreach(data_get($tourPackages, 'domestic', []) as $package)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                    <div class="aspect-video relative overflow-hidden bg-slate-200">
                        <img src="{{ data_get($package, 'image') }}" alt="{{ data_get($package, 'alt') }}" class="w-full h-full object-cover">
                        @if(data_get($package, 'label'))
                            <span class="absolute top-4 left-4 text-[10px] font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2.5 py-1 rounded-full shadow-sm">{{ data_get($package, 'label') }}</span>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">{{ data_get($package, 'title') }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ data_get($package, 'subtitle') }}</p>
                            <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                                {{ data_get($package, 'description') }}
                            </p>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block">Starting from</span>
                                <span class="font-black text-[#216417] text-lg">{{ data_get($package, 'price') }}<span class="text-xs font-normal text-slate-400">/pax</span></span>
                            </div>
                            <a href="{{ data_get($package, 'button_link') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">{{ data_get($package, 'button_text') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- International Packages Tab -->
        <div id="international-packages" x-show="activeTab === 'international'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="display:none;">
            {{-- Server-side fallback packages (will be replaced by client-side JS when available) --}}
            @foreach(data_get($tourPackages, 'international', []) as $package)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                    <div class="aspect-video relative overflow-hidden bg-slate-200">
                        <img src="{{ data_get($package, 'image') }}" alt="{{ data_get($package, 'alt') }}" class="w-full h-full object-cover">
                        @if(data_get($package, 'label'))
                            <span class="absolute top-4 left-4 text-[10px] font-bold text-pink-600 uppercase tracking-widest bg-pink-50 px-2.5 py-1 rounded-full shadow-sm">{{ data_get($package, 'label') }}</span>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">{{ data_get($package, 'title') }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ data_get($package, 'subtitle') }}</p>
                            <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                                {{ data_get($package, 'description') }}
                            </p>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block">Starting from</span>
                                <span class="font-black text-[#216417] text-lg">{{ data_get($package, 'price') }}<span class="text-xs font-normal text-slate-400">/pax</span></span>
                            </div>
                            <a href="{{ data_get($package, 'button_link') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">{{ data_get($package, 'button_text') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Additional List of Supported Destinations -->
        <div class="mt-16 bg-white rounded-[2rem] p-8 sm:p-12 shadow-md ring-1 ring-slate-100">
            <h3 class="text-xl font-bold text-slate-900 text-center mb-8">{{ data_get($pageContent, 'supported_destinations_title', 'All Supported Destinations') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($supportedDestinations as $group)
                    <div>
                        <h4 class="font-bold text-[#216417] text-sm uppercase tracking-wide mb-3">{{ data_get($group, 'title') }}</h4>
                        <ul class="text-sm text-slate-500 space-y-2">
                            @foreach(data_get($group, 'destinations', []) as $destination)
                                <li>{{ $destination }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const domContainer = document.getElementById('domestic-packages');
    const intlContainer = document.getElementById('international-packages');

    function cardHtml(pkg) {
        const image = pkg.image || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80';
        const label = pkg.promo || pkg.tag || '';
        const title = pkg.tour_name || pkg.name || '';
        const subtitle = pkg.duration || pkg.detail || '';
        const desc = pkg.highlights || pkg.inclusions || pkg.desc || '';
        const price = pkg.price_per_pax || pkg.price || '';
            // Build a book link that pre-fills the booking form via query params
            const params = new URLSearchParams();
            if (pkg.destinations) params.set('destination', pkg.destinations);
            if (pkg.departure) params.set('origin', pkg.departure);

            // Determine mode: prefer explicit mode_of_transportation, else airline
            if (pkg.mode_of_transportation) {
                const explicitMode = pkg.mode_of_transportation.toString().trim().toLowerCase();
                if (explicitMode.includes('airline')) {
                    params.set('mode', 'airline');
                } else if (explicitMode.includes('ferry')) {
                    params.set('mode', 'ferry');
                }
            } else if (pkg.airline && pkg.airline.toString().trim() !== '' && pkg.airline.toString().toLowerCase() !== 'n/a') {
                params.set('mode', 'airline');
            }

            let durationDays = null;
            if (pkg.duration_days) {
                durationDays = pkg.duration_days;
            } else if (pkg.duration) {
                const m = pkg.duration.toString().match(/(\d+)\s*[dD]/);
                if (m && m[1]) {
                    durationDays = m[1];
                } else {
                    const m2 = pkg.duration.toString().match(/(\d+)\s*day/i);
                    if (m2 && m2[1]) {
                        durationDays = m2[1];
                    }
                }
            }

            if (pkg.trip_type) {
                const tripType = pkg.trip_type.toString().trim().toLowerCase();
                if (tripType.includes('round')) {
                    params.set('trip_type', 'round_trip');
                } else if (tripType.includes('one')) {
                    params.set('trip_type', 'one_way');
                }
            }

            // Use parsed dates from the API when available; otherwise try to parse raw available_dates
            if (pkg.available_dates_parsed && Array.isArray(pkg.available_dates_parsed) && pkg.available_dates_parsed.length > 0) {
                // pass the list as a comma-separated param and pick the first as default
                params.set('available_dates', pkg.available_dates_parsed.join(','));
                params.set('departure_date', pkg.available_dates_parsed[0]);
            } else if (pkg.available_dates) {
                const raw = pkg.available_dates.toString();
                if (!/not\s*specified/i.test(raw)) {
                    const candidates = raw.split(/[,;|\/]+/).map(s => s.trim()).filter(Boolean);
                    let picked = '';
                    const pickedList = [];
                    for (const c of candidates) {
                        if (/^\d{4}-\d{2}-\d{2}$/.test(c)) { pickedList.push(c); if (!picked) picked = c; continue; }
                        const d = new Date(c);
                        if (!isNaN(d.getTime())) {
                            const y = d.getFullYear();
                            const m = String(d.getMonth() + 1).padStart(2, '0');
                            const day = String(d.getDate()).padStart(2, '0');
                            const iso = `${y}-${m}-${day}`;
                            pickedList.push(iso);
                            if (!picked) picked = iso;
                        }
                    }
                    if (pickedList.length) {
                        params.set('available_dates', pickedList.join(','));
                        params.set('departure_date', pickedList[0]);
                    } else if (picked) {
                        params.set('departure_date', picked);
                    }
                }
            }

            // Send duration_days if provided
            if (durationDays) {
                params.set('duration_days', durationDays);
            }

            // Default to round-trip when a duration implies a multi-day package
            const computedDurationDays = params.get('duration_days');
            if (!params.has('trip_type') && computedDurationDays && parseInt(computedDurationDays, 10) > 1) {
                params.set('trip_type', 'round_trip');
            }

            // Automatically compute return date for fixed-duration round-trip packages
            const departureDate = params.get('departure_date');
            const tripType = params.get('trip_type');
            const returnDurationDays = params.get('duration_days');
            if (departureDate && returnDurationDays && tripType === 'round_trip') {
                const d = new Date(departureDate);
                const days = parseInt(returnDurationDays, 10);
                if (!isNaN(days) && days > 1 && !isNaN(d.getTime())) {
                    d.setDate(d.getDate() + days - 1);
                    const returnIso = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                    params.set('return_date', returnIso);
                }
            }

            if (pkg.hotel) params.set('hotel', pkg.hotel);
            if (pkg.price_per_pax) params.set('price', pkg.price_per_pax);
            if (pkg.tour_name) params.set('package_name', pkg.tour_name);
            params.set('adults', '1');
            const link = '/book/new?' + params.toString();

        return `
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="${image}" alt="${title}" class="w-full h-full object-cover">
                    ${label ? `<span class="absolute top-4 left-4 text-[10px] font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2.5 py-1 rounded-full shadow-sm">${label}</span>` : ''}
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">${title}</h3>
                        <p class="text-xs text-slate-400 mt-1">${subtitle}</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">${desc}</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">${price}<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="${link}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>
        `;
    }

    fetch('/api/tours')
        .then(r => r.ok ? r.json() : Promise.reject(r))
        .then(list => {
            const domestic = [];
            const international = [];
            list.forEach(pkg => {
                const country = (pkg.country || '').toString().toLowerCase();
                if (country.includes('philipp')) domestic.push(pkg);
                else international.push(pkg);
            });

            if (domContainer) {
                domContainer.innerHTML = domestic.map(cardHtml).join('');
            }
            if (intlContainer) {
                intlContainer.innerHTML = international.map(cardHtml).join('');
            }
        })
        .catch(err => {
            console.error('Could not load tours:', err);
        });
});
</script>
@endpush
@endsection
