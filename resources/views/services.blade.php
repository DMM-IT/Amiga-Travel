@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">{{ $pageContent['badge'] ?? 'Services' }}</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">{{ $pageContent['title'] ?? 'Our Travel Services' }}</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $pageContent['description'] ?? 'Explore a full range of reliable travel, transit ticketing, and customizable packages designed to make your journey completely hassle-free.' }}
            </p>
        </div>

        @php
            $serviceCta = $pageContent['service_cta'] ?? [
                'badge' => 'New Booking System',
                'title' => 'Book Ferry Tickets Directly Online',
                'description' => 'Quickly check available schedules, fares, and cabins for 2GO Travel and Starlite Ferries Inc. Complete your passenger credentials and print tickets instantly.',
                'button_text' => 'Start Direct Booking',
            ];
            $serviceCards = $pageContent['service_cards'] ?? [
                [
                    'icon' => 'M13 5l7 7-7 7M5 5l7 7-7 7',
                    'title' => '2GO Travel Booking',
                    'description' => 'Book premier overnight ship accommodation and fast cargo transits with 2GO Travel. Ideal for family retreats, business logistics, and leisure trips.',
                    'note' => 'Available Online',
                    'link' => url('/book/new'),
                    'color' => 'text-pink-600',
                ],
                [
                    'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                    'title' => 'Starlite Ferries Inc.',
                    'description' => 'Affordable regional transits between Batangas, Calapan, and Roxas. We manage standard ferry bookings and roll-on/roll-off (RoRo) cargo slots.',
                    'note' => 'Available Online',
                    'link' => url('/book/new'),
                    'color' => 'text-emerald-700',
                ],
                [
                    'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                    'title' => 'Airline Ticketing',
                    'description' => 'Domestic & international flights powered by leading carriers including AirAsia, Cebu Pacific, and Philippine Airlines (PAL). Hassle-free check-ins and seat bookings.',
                    'note' => 'PAL, CebuPac, AirAsia',
                    'link' => url('/contact-us'),
                    'color' => 'text-blue-600',
                ],
                [
                    'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
                    'title' => 'Tour Packages',
                    'description' => 'Curated itineraries for both local (Puerto Galera, El Nido, Boracay) and international (Thailand, Japan, Korea) travel hotspots. Complete with accommodations and guides.',
                    'note' => 'Local & International',
                    'link' => url('/tour-package'),
                    'color' => 'text-purple-600',
                ],
                [
                    'icon' => 'M12 14l9-5-9-5-9 5 9 5z',
                    'title' => 'Apprenticeships & Training',
                    'description' => 'Custom-tailored hospitality training programs, onboard apprenticeship training options, and educational field trips in cooperation with 2GO.',
                    'note' => 'For Academe & Students',
                    'link' => url('/contact-us'),
                    'color' => 'text-orange-600',
                ],
                [
                    'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    'title' => 'Custom Travel Arrangements',
                    'description' => 'Tailored travel packages for corporate retreats, family reunions, and large groups. We handle flight connections, hotel accommodation blocks, and group transport.',
                    'note' => 'Tailored For Groups',
                    'link' => url('/contact-us'),
                    'color' => 'text-teal-700',
                ],
            ];
        @endphp
        <div class="bg-gradient-to-br from-[#216417] to-[#14400e] text-white rounded-[2rem] p-8 sm:p-12 shadow-xl mb-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl text-center md:text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-300 bg-white/10 px-3 py-1 rounded-full">{{ data_get($serviceCta, 'badge') }}</span>
                <h2 class="mt-3 text-2xl sm:text-3xl font-black">{{ data_get($serviceCta, 'title') }}</h2>
                <p class="mt-2 text-emerald-100/90 text-sm sm:text-base">
                    {{ data_get($serviceCta, 'description') }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ url('/book/new') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-900 font-bold rounded-full shadow-lg hover:bg-emerald-50 transition cursor-pointer">
                    {{ data_get($serviceCta, 'button_text') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($serviceCards as $card)
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-slate-100 rounded-2xl flex items-center justify-center text-current mb-6 {{ data_get($card, 'color') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ data_get($card, 'icon') }}" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">{{ data_get($card, 'title') }}</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    {{ data_get($card, 'description') }}
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">{{ data_get($card, 'note') }}</span>
                    <a href="{{ data_get($card, 'link') }}" class="text-sm font-bold {{ data_get($card, 'color') }} hover:opacity-80 transition">{{ data_get($card, 'button_text', 'Learn more') }} &rarr;</a>
                </div>
            </div>
            @endforeach
@endsection
