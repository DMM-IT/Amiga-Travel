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
        <div x-show="activeTab === 'domestic'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
        <div x-show="activeTab === 'international'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="display:none;">
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
@endsection
