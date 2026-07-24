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
            <div class="relative max-w-lg w-full rounded-2xl bg-white p-6 z-10 shadow-lg">
                <h3 class="text-lg font-semibold text-slate-900">Want to cancel your booking?</h3>
                <p class="mt-3 text-sm text-slate-700">We received your proof of payment. If you change your mind, you can start a 5-minute cancellation window now to request a refund. After 5 minutes, cancellation will no longer be available.</p>
                <div class="mt-4 flex gap-3 justify-end">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Maybe later</a>
                    <a href="{{ url('/book/status?transaction_number=' . urlencode($suggestTxn) . '&start_cancellation=1') }}" class="inline-flex items-center justify-center rounded-3xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-700">Start cancellation</a>
                </div>
            </div>
        </div>
    </div>
@endif
@if(session()->has('booking_draft'))
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="rounded-[1.5rem] border border-pink-200 bg-pink-50 p-4 text-slate-900 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-pink-700">You have a pending booking in progress.</p>
                    <p class="mt-1 text-xs text-slate-600">Return to complete your booking or cancel the draft to start a new one.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ url('/book/new') }}" class="inline-flex items-center justify-center rounded-full bg-pink-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-pink-700">Return to booking</a>
                    <form method="POST" action="{{ route('booking.draft.cancel') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-pink-600 px-4 py-2 text-xs font-semibold text-pink-700 transition hover:bg-pink-100">Cancel draft</button>
                    </form>
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
                        '{{ asset('storage/' . $heroImage) }}',
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
            <div class="w-full h-full min-h-[280px] lg:min-h-[600px] flex flex-col items-center justify-center p-6 text-center bg-slate-50 aspect-[16/9] lg:aspect-[3/4]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-medium text-slate-500">Exciting Deals Coming Soon!</h3>
                <p class="mt-2 text-sm text-slate-400">Check back later for special promotions and announcements.</p>
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
                animation: marquee 20s linear infinite;
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
            <div class="px-6 py-8 sm:px-10 sm:py-10 text-center flex-shrink-0" style="background: linear-gradient(135deg, #216417 0%, #14400e 100%);">
                <h1 class="text-xl sm:text-2xl font-semibold text-white">{{ $pageContent['welcome_title'] ?? 'Welcome to Amiga Gracia Travel Services' }}</h1>
                <p class="mt-3 text-sm sm:text-base text-white/85 max-w-lg mx-auto">{{ $pageContent['welcome_subtitle'] ?? 'Your Journey Deserves More Than A Destination — It Deserves An Exceptional Experience' }}</p>
            </div>
 
            <div class="p-5 sm:p-7 grid gap-5 sm:grid-cols-2 flex-grow">
                <a href="{{ url('/book/new') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col" style="background: linear-gradient(135deg, #eaf5e8 0%, #d8eed2 100%); --hover-border: #216417;" onmouseover="this.style.borderColor='#216417'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#216417;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">Book a Trip</h2>
                    <p class="mt-1 text-xs text-slate-600 flex-grow">Start a new booking — choose your route, schedule, passengers, and accommodations.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#216417;">Get started →</span>
                </a>
 
                <a href="{{ url('/book/status') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col" style="background: linear-gradient(135deg, #fde6f3 0%, #f9cce6 100%);" onmouseover="this.style.borderColor='#ee018d'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#ee018d;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.2 4.2a7.5 7.5 0 0012.45 12.45z" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">Check My Booking</h2>
                    <p class="mt-1 text-xs text-slate-600 flex-grow">Already booked? Enter your transaction number to view your booking details and status.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#ee018d;">Check status →</span>
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

        <p class="text-xs text-slate-500 mt-2">
            {{ data_get($pageContent, 'booking_section_description', 'Kay Amiga, Hassle Free Ka! Select a booking category to start your transaction request.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @php
            $bookingCards = data_get($pageContent, 'content.booking_cards', data_get($pageContent, 'booking_cards', []));
            $totalCardsNeeded = 6;
            $cards = [];
            
            // Add existing cards
            foreach ($bookingCards as $card) {
                if (count($cards) >= $totalCardsNeeded) {
                    break;
                }
                $cards[] = $card;
            }
            
            // Add placeholder cards if needed
            while (count($cards) < $totalCardsNeeded) {
                $cards[] = [
                    'title' => 'Travel Booking',
                    'description' => 'Kasiyahan po namin ang paglingkuran kayo.',
                    'image' => null,
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
                    ? asset('storage/' . $rawCardImage)
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';
                
                $cardTitle = data_get($card, 'title', 'Travel Booking');
                $cardDescription = data_get($card, 'description', 'Kasiyahan po namin ang paglingkuran kayo.');
            @endphp
            <a href="{{ url('/book/new') }}" class="group rounded-[2rem] bg-white border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col overflow-hidden">
                <img src="{{ $cardImage }}" alt="{{ $cardTitle }}" class="w-full aspect-video object-cover">
                <div class="p-6 flex flex-col flex-grow">
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-[#ee018d] uppercase tracking-wider mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 15v-4H7l5-7v4h4l-5 7z"/>
                        </svg>
                        Amiga - Best Travel Buddy
                    </span>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $cardTitle }}</h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">{{ $cardDescription }}</p>
                    <button class="w-full bg-[#ee018d] text-white text-sm font-bold py-3 px-6 rounded-full hover:bg-pink-700 transition-colors">
                        {{ data_get($card, 'booking_button_text', 'Book Now') }}
                    </button>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
