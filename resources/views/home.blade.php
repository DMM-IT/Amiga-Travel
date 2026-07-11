@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 flex flex-col lg:flex-row items-stretch gap-6">
    <!-- Left Column: Scrolling Image Carousel -->
    <div class="w-full lg:w-1/2 rounded-[2rem] overflow-hidden shadow-xl ring-1 ring-slate-200 relative bg-white flex items-center justify-center"
         x-data="{
            activeSlide: 0,
            slides: [
                @if($promotions->count() > 0)
                    @foreach($promotions as $promo)
                        '{{ asset('storage/' . $promo->image_path) }}',
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
            <div class="w-full h-full min-h-[380px] relative group">
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
            <div class="w-full h-full min-h-[380px] flex flex-col items-center justify-center p-6 text-center bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-medium text-slate-500">Exciting Deals Coming Soon!</h3>
                <p class="mt-2 text-sm text-slate-400">Check back later for special promotions and announcements.</p>
            </div>
        </template>
    </div>

    <!-- Right Column: Form -->
    <div class="w-full lg:w-1/2">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden h-full flex flex-col">
            <div class="px-6 py-6 sm:px-10 sm:py-8 text-center flex-shrink-0" style="background: linear-gradient(135deg, #216417 0%, #14400e 100%);">
                <h1 class="text-xl sm:text-2xl font-semibold text-white">Welcome to Amiga Gracia Travel Services</h1>
                <p class="mt-2 text-xs sm:text-sm text-white/85 max-w-lg mx-auto">Ferry bookings, accommodations, and everything in between — made easy. What would you like to do today?</p>
            </div>

            <div class="p-4 sm:p-6 grid gap-4 sm:grid-cols-2 flex-grow">
                <a href="{{ url('/book/new') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col" style="--hover-border: #216417;" onmouseover="this.style.borderColor='#216417'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eaf5e8;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#216417;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">Book a Trip</h2>
                    <p class="mt-1 text-xs text-slate-500 flex-grow">Start a new booking — choose your route, schedule, passengers, and accommodations.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#216417;">Get started →</span>
                </a>

                <a href="{{ url('/book/status') }}" class="group rounded-[1.5rem] border-2 border-slate-200 p-4 text-left transition duration-200 hover:shadow-md flex flex-col" onmouseover="this.style.borderColor='#ee018d'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fde6f3;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#ee018d;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.2 4.2a7.5 7.5 0 0012.45 12.45z" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-semibold text-slate-900">Check My Booking</h2>
                    <p class="mt-1 text-xs text-slate-500 flex-grow">Already booked? Enter your transaction number to view your booking details and status.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color:#ee018d;">Check status →</span>
                </a>
            </div>

            <div class="px-6 pb-4 sm:px-10 text-center flex-shrink-0">
                <p class="text-[10px] text-slate-400">Amiga Gracia Travel Services · travel-2go.com.ph</p>
            </div>
        </div>
    </div>
</div>

<!-- Booking Request Cards -->
<div class="max-w-7xl mx-auto px-4 pb-12 mt-10">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-black text-[#216417]">Request Travel Bookings</h2>
        <p class="text-xs text-slate-500 mt-2">Kay Amiga, Hassle Free Ka! Select a booking category to start your transaction request.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1: 2GO Ferry -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=600&q=80" alt="2GO Ferry" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR 2GO FERRY TICKET NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: Starlite Ferry -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=80" alt="Starlite Ferry" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR STARLITE FERRY TICKET NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: Air Asia -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&q=80" alt="Air Asia" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR AIR ASIA TICKET NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 4: Cebu Pacific -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1517999144091-3d9dca6d1e43?auto=format&fit=crop&w=600&q=80" alt="Cebu Pacific" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR CEBU PACIFIC TICKET NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 5: Philippine Airlines -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1483450388369-9ed95738483c?auto=format&fit=crop&w=600&q=80" alt="Philippine Airlines" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR PHILIPPINE AIRLINE TICKET NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 6: Travel With Us -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-200 flex flex-col hover:shadow-lg transition duration-200">
            <div class="aspect-video w-full overflow-hidden bg-slate-100">
                <img src="https://images.unsplash.com/photo-1516690561799-46d8f74f90f6?auto=format&fit=crop&w=600&q=80" alt="Travel With Us" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight uppercase">BOOK YOUR TRAVEL WITH US NOW</h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-medium">
                        Kasiyahan po namin ang paglingkuran kayo.
                    </p>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Amiga - Best Travel Buddy</p>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/book/new') }}"
                       class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-[#ee018d] hover:bg-pink-700 text-white text-xs font-bold rounded-full transition shadow-sm cursor-pointer">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
