@extends('layouts.app')

@section('content')
@php
    $showCancelSuggestion = request()->query('show_cancel_suggestion');
    $suggestTxn = request()->query('transaction_number');
@endphp
@if($showCancelSuggestion)
    <div x-data="{ open: true }" x-init="open = true">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
            <div class="relative max-w-lg w-full rounded-2xl bg-white p-6 z-10 shadow-lg relative ws-sbtn-container">
                @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'cancel_modal' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
                <h3 class="text-lg font-semibold text-slate-900">{{ data_get($pageContent, 'cancel_modal_title', 'Want to cancel your booking?') }}</h3>
                <p class="mt-3 text-sm text-slate-700">{{ data_get($pageContent, 'cancel_modal_desc', 'We received your proof of payment. If you change your mind, you can start a 5-minute cancellation window now to request a refund. After 5 minutes, cancellation will no longer be available.') }}</p>
                <div class="mt-4 flex gap-3 justify-end">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ data_get($pageContent, 'cancel_modal_btn_cancel', 'Maybe later') }}</a>
                    <a href="{{ url('/book/status?transaction_number=' . urlencode($suggestTxn) . '&start_cancellation=1') }}" class="inline-flex items-center justify-center rounded-3xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-700">{{ data_get($pageContent, 'cancel_modal_btn_confirm', 'Start cancellation') }}</a>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="max-w-7xl mx-auto px-0 lg:px-4 py-0 lg:py-6 flex flex-col lg:flex-row items-start gap-0 lg:gap-6">
    <!-- Left Column: Scrolling Image Carousel -->
    <div class="w-full lg:w-4/12 rounded-none lg:rounded-[2rem] overflow-hidden shadow-none lg:shadow-xl ring-0 lg:ring-1 ring-slate-200 relative bg-white flex items-center justify-center min-h-[280px] sm:min-h-[360px] lg:min-h-[520px]"
         x-data="{
            activeSlide: 0,
            slides: [
                @if($heroImages->count() > 0)
                    @foreach($heroImages as $heroImage)
                            '{{ storage_asset_path($heroImage) }}',
                        @endforeach
                @endif
            ],
            init() {
                if (this.slides.length > 1) {
                    setInterval(() => {
                        this.next();
                    }, 5000);
                }
            },
            next() {
                this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
            },
            prev() {
                this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1;
            }
         }">
        
        <template x-if="slides.length > 0">
            <div class="w-full h-full relative group aspect-[16/9] lg:aspect-[3/4]">
                <!-- Images -->
                <template x-for="(slide, index) in slides" :key="index">
                    <img :src="slide" 
                         x-show="activeSlide === index"
                         x-transition.opacity.duration.500ms
                         alt="Promotion" 
                         class="absolute inset-0 w-full h-full object-cover">
                </template>

                <!-- Prev/Next Buttons -->
                <div class="absolute inset-0 flex items-center justify-between p-4 opacity-0 group-hover:opacity-100 transition-opacity" x-show="slides.length > 1">
                    <button @click="prev()" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="next()" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- Dots -->
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2" x-show="slides.length > 1">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index" 
                                :class="{'bg-white': activeSlide === index, 'bg-white/50': activeSlide !== index}" 
                                class="w-3 h-3 rounded-full shadow-sm transition-colors"></button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="slides.length === 0">
            <div class="w-full h-full min-h-[280px] lg:min-h-[600px] flex flex-col items-center justify-center p-6 text-center bg-slate-50 aspect-[16/9] lg:aspect-[3/4] relative ws-sbtn-container">
                @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'deals_empty' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-medium text-slate-500">{{ data_get($pageContent, 'deals_empty_title', 'Exciting Deals Coming Soon!') }}</h3>
                <p class="mt-2 text-sm text-slate-400">{{ data_get($pageContent, 'deals_empty_desc', 'Check back later for special promotions and announcements.') }}</p>
            </div>
        </template>
    </div>

    <!-- Right Column: Form -->
    <div class="w-full lg:w-8/12 px-4 py-6 lg:px-0 lg:py-0 flex flex-col gap-14 justify-between">
        {{-- Sliding Promotional Text --}}
        <style>
            @keyframes marquee {
                0% { transform: translateX(0%); }
                100% { transform: translateX(-50%); }
            }
            .animate-marquee-infinite {
                animation: marquee 50s linear infinite;
                width: max-content;
            }
        </style>
        @php
            $slidingText = data_get($pageContent, 'sliding_text', 'Your Journey Deserves More Than A Destination — It Deserves An Exceptional Experience');
        @endphp
        <div class="overflow-hidden rounded-[1.5rem] bg-[#ee018d] shadow-lg relative flex items-center py-6 sm:py-10">
            <div class="absolute top-0 bottom-0 left-0 w-20 sm:w-24 bg-gradient-to-r from-[#ee018d] to-transparent z-10 pointer-events-none"></div>
            <div class="absolute top-0 bottom-0 right-0 w-20 sm:w-24 bg-gradient-to-l from-[#ee018d] to-transparent z-10 pointer-events-none"></div>
            
            <div class="animate-marquee-infinite whitespace-nowrap flex text-lg sm:text-2xl lg:text-3xl font-black text-white tracking-wide">
                @for($i = 0; $i < 6; $i++)
                <span class="px-8 flex items-center gap-4">
                    <img src="{{ asset('images/amiga-logo-transparent.png') }}" alt="Amiga Gracia" class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 bg-white rounded-full p-1">
                    {{ $slidingText }}
                </span>
                @endfor
            </div>
        </div>

        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden flex-grow flex flex-col">
            <div class="relative px-6 py-8 sm:px-10 sm:py-10 text-center flex-shrink-0 overflow-hidden" style="background: linear-gradient(135deg, #216417 0%, #14400e 100%);">
                <!-- Subtle Tribal Pattern Overlay -->
                <div class="absolute inset-0 pointer-events-none" style="background-image: url('{{ asset('images/tribal-pattern.svg') }}'); background-repeat: repeat; opacity: 0.12;"></div>

                <!-- Minimalist Geometric Decorative SVG (Left) -->
                <svg class="absolute -left-10 -top-10 w-44 h-44 text-white/10 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" stroke="currentColor" stroke-width="2" stroke-dasharray="8 8" />
                    <circle cx="100" cy="100" r="50" stroke="currentColor" stroke-width="1.5" />
                    <circle cx="100" cy="100" r="20" fill="currentColor" fill-opacity="0.5" />
                </svg>

                <!-- Minimalist Geometric Decorative SVG (Right) -->
                <svg class="absolute -right-12 -bottom-12 w-52 h-52 text-white/10 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" stroke="currentColor" stroke-width="2" />
                    <circle cx="100" cy="100" r="65" stroke="currentColor" stroke-width="1.5" stroke-dasharray="12 6" />
                    <path d="M20 100 H180 M100 20 V180" stroke="currentColor" stroke-width="1" stroke-opacity="0.6" />
                </svg>

                <div class="relative z-10">
                    <h1 class="text-xl sm:text-2xl font-bold text-white tracking-wide">{{ $pageContent['welcome_title'] ?? 'Welcome to Amiga Gracia Travel Services' }}</h1>
                    <p class="mt-3 text-sm sm:text-base text-white/90 max-w-lg mx-auto font-medium">{{ $pageContent['welcome_subtitle'] ?? 'Your Journey Deserves More Than A Destination — It Deserves An Exceptional Experience' }}</p>
                </div>
            </div>
 
            <div class="p-5 sm:p-7 grid gap-5 sm:grid-cols-2 flex-grow">
                <a href="{{ url('/book/new') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col relative ws-sbtn-container" style="background: linear-gradient(135deg, #eaf5e8 0%, #d8eed2 100%); --hover-border: #216417;" onmouseover="this.style.borderColor='#216417'" onmouseout="this.style.borderColor=''">
                    @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'action_cards' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#216417;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">{{ data_get($pageContent, 'action_book_title', 'Book a Trip') }}</h2>
                    <p class="mt-1 text-xs text-slate-600 flex-grow">{{ data_get($pageContent, 'action_book_desc', 'Start a new booking — choose your route, schedule, passengers, and accommodations.') }}</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#216417;">{{ data_get($pageContent, 'action_book_btn', 'Get started →') }}</span>
                </a>
 
                <a href="{{ url('/book/status') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col relative ws-sbtn-container" style="background: linear-gradient(135deg, #fde6f3 0%, #f9cce6 100%);" onmouseover="this.style.borderColor='#ee018d'" onmouseout="this.style.borderColor=''">
                    @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'action_cards' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#ee018d;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.2 4.2a7.5 7.5 0 0012.45 12.45z" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">{{ data_get($pageContent, 'action_status_title', 'Check My Booking') }}</h2>
                    <p class="mt-1 text-xs text-slate-600 flex-grow">{{ data_get($pageContent, 'action_status_desc', 'Already booked? Enter your transaction number to view your booking details and status.') }}</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#ee018d;">{{ data_get($pageContent, 'action_status_btn', 'Check status →') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Booking Request Cards --}}
<div class="max-w-7xl mx-auto px-4 pb-12 mt-10">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-black text-[#216417]">
            {{ data_get($pageContent, 'booking_section_title', 'Request Travel Bookings') }}
        </h2>

        <p class="text-sm text-black font-semibold mt-2">
            {{ data_get($pageContent, 'booking_section_description', 'Kay Amiga, Hassle Free Ka! Select a booking category to start your transaction request.') }}
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
        @php
            $bookingCards = data_get($pageContent, 'content.booking_cards', data_get($pageContent, 'booking_cards', []));
            $defaultBookingCards = [
                [
                    'title' => '2GO Travel',
                    'description' => 'Book premier overnight ship accommodation and fast cargo transits with 2GO Travel.',
                    'image' => 'images/2GO-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('2GO Travel') . '&trip_type=one_way&mode=ferry',
                ],
                [
                    'title' => 'Starlite Ferries Inc.',
                    'description' => 'Affordable regional ferry departures between Batangas, Calapan, and Roxas.',
                    'image' => 'images/Starlite_Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Starlite Ferries Inc.') . '&trip_type=one_way&mode=ferry',
                ],
                [
                    'title' => 'Cebu Pacific',
                    'description' => 'Search daily flights and budget fares across the Philippines and Asia.',
                    'image' => 'images/CebuPecific-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Cebu Pacific') . '&trip_type=one_way&mode=airline',
                ],
                [
                    'title' => 'Philippine Airlines',
                    'description' => 'Book Philippine Airlines flights with premium support and flexible fare options.',
                    'image' => 'images/Pal-Logo.jfif',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('Philippine Airlines') . '&trip_type=one_way&mode=airline',
                ],
                [
                    'title' => 'AirAsia',
                    'description' => 'Find low-cost airline tickets and convenient domestic connections.',
                    'image' => 'images/AirAsia-Logo.png',
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new?operator=' . urlencode('AirAsia') . '&trip_type=one_way&mode=airline',
                ],
            ];

            $totalCardsNeeded = 5;
            $cards = ! empty($bookingCards) ? array_values($bookingCards) : $defaultBookingCards;
            $cards = array_slice($cards, 0, $totalCardsNeeded);

            while (count($cards) < $totalCardsNeeded) {
                $cards[] = [
                    'title' => 'Travel Booking',
                    'description' => 'Kasiyahan po namin ang paglingkuran kayo.',
                    'image' => null,
                    'booking_button_text' => 'Book Now',
                    'link' => '/book/new',
                ];
            }
        @endphp

        @foreach($cards as $card)
            @php
                $rawCardImage = data_get($card, 'image');
                
                if (is_array($rawCardImage)) {
                    $rawCardImage = array_values(array_filter($rawCardImage))[0] ?? null;
                }

                $cardImage = $rawCardImage
                    ? (str_starts_with($rawCardImage, 'http://') || str_starts_with($rawCardImage, 'https://')
                        ? $rawCardImage
                        : (str_starts_with($rawCardImage, 'images/')
                            ? asset($rawCardImage)
                            : (storage_asset_path($rawCardImage) ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80')))
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';

                $cardTitle = data_get($card, 'title', 'Travel Booking');
                $cardDescription = data_get($card, 'description', 'Kasiyahan po namin ang paglingkuran kayo.');
                $cardLink = data_get($card, 'link', '/book/new');
                $bookingText = data_get($card, 'booking_button_text', 'Book Now');
            @endphp
            <a href="{{ url($cardLink) }}" class="group rounded-xl sm:rounded-[2rem] bg-white border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col overflow-hidden">
                <div class="h-20 sm:h-36 w-full bg-white flex items-center justify-center p-2 sm:p-8 border-b border-slate-100">
                    <img src="{{ $cardImage }}" alt="{{ $cardTitle }}" class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';">
                </div>
                <div class="p-2.5 sm:p-6 flex flex-col flex-grow">
                    <span class="inline-flex items-center gap-1 text-[8px] sm:text-[10px] font-semibold text-[#ee018d] uppercase tracking-wider mb-1 sm:mb-3 leading-tight truncate">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 15v-4H7l5-7v4h4l-5 7z"/>
                        </svg>
                        <span class="truncate">Amiga - Best Travel Buddy</span>
                    </span>
                    <h3 class="text-xs sm:text-lg font-bold text-slate-900 mb-1 sm:mb-2 leading-tight truncate">{{ $cardTitle }}</h3>
                    <p class="text-[9px] sm:text-sm text-slate-600 mb-2 sm:mb-4 flex-grow line-clamp-2 sm:line-clamp-none leading-tight">{{ $cardDescription }}</p>
                    <button class="w-full bg-[#ee018d] text-white text-[10px] sm:text-sm font-bold py-1.5 px-2 sm:py-3 sm:px-6 rounded-full hover:bg-pink-700 transition-colors leading-tight">
                        {{ $bookingText }}
                    </button>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
