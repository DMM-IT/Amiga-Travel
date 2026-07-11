@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Services</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">Our Travel Services</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                Explore a full range of reliable travel, transit ticketing, and customizable packages designed to make your journey completely hassle-free.
            </p>
        </div>

        <!-- Built-in Booking CTA -->
        <div class="bg-gradient-to-br from-[#216417] to-[#14400e] text-white rounded-[2rem] p-8 sm:p-12 shadow-xl mb-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl text-center md:text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-300 bg-white/10 px-3 py-1 rounded-full">New Booking System</span>
                <h2 class="mt-3 text-2xl sm:text-3xl font-black">Book Ferry Tickets Directly Online</h2>
                <p class="mt-2 text-emerald-100/90 text-sm sm:text-base">
                    Quickly check available schedules, fares, and cabins for 2GO Travel and Starlite Ferries. Complete your passenger credentials and print tickets instantly.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ url('/book/new') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-900 font-bold rounded-full shadow-lg hover:bg-emerald-50 transition cursor-pointer">
                    Start Direct Booking
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Service 1: 2GO Ferry -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-pink-100 rounded-2xl flex items-center justify-center text-pink-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">2GO Travel Booking</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Book premier overnight ship accommodation and fast cargo transits with 2GO Travel. Ideal for family retreats, business logistics, and leisure trips.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">Available Online</span>
                    <a href="{{ url('/book/new') }}" class="text-sm font-bold text-pink-600 hover:text-pink-700 transition">Request Form &rarr;</a>
                </div>
            </div>

            <!-- Service 2: Starlite & Supercat -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-700 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Starlite & Supercat</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Affordable regional transits between Batangas, Calapan, and Roxas. We manage standard ferry bookings, roll-on/roll-off (RoRo) cargo slots, and fastcraft ticketing.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">Available Online</span>
                    <a href="{{ url('/book/new') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800 transition">Book direct &rarr;</a>
                </div>
            </div>

            <!-- Service 3: Flight Bookings -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Airline Ticketing</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Domestic & international flights powered by leading carriers including <strong>AirAsia, Cebu Pacific, and Philippine Airlines (PAL)</strong>. Hassle-free check-ins and seat bookings.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">PAL, CebuPac, AirAsia</span>
                    <a href="{{ url('/contact-us') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition">Inquire &rarr;</a>
                </div>
            </div>

            <!-- Service 4: Tour Packages -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Tour Packages</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Curated itineraries for both local (Puerto Galera, El Nido, Boracay) and international (Thailand, Japan, Korea) travel hotspots. Complete with accommodations and guides.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">Local & International</span>
                    <a href="{{ url('/tour-package') }}" class="text-sm font-bold text-purple-600 hover:text-purple-700 transition">View packages &rarr;</a>
                </div>
            </div>

            <!-- Service 5: Apprenticeship & Educational Tours -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Apprenticeships & Training</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Custom-tailored hospitality training programs, onboard apprenticeship training options, and educational field trips in cooperation with 2GO.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">For Academe & Students</span>
                    <a href="{{ url('/contact-us') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700 transition">Apply now &rarr;</a>
                </div>
            </div>

            <!-- Service 6: Custom Travel Arrangements -->
            <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition duration-200">
                <div class="h-12 w-12 bg-teal-100 rounded-2xl flex items-center justify-center text-teal-700 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Custom Group Packages</h3>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed flex-grow">
                    Tailored travel packages for corporate retreats, family reunions, and large groups. We handle flight connections, hotel accommodation blocks, and group transport.
                </p>
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">Tailored For Groups</span>
                    <a href="{{ url('/contact-us') }}" class="text-sm font-bold text-teal-700 hover:text-teal-800 transition">Request Quote &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
