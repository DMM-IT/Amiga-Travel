@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('tour-package') }}" class="inline-flex items-center text-slate-600 hover:text-emerald-700 font-semibold text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Packages
            </a>
        </div>

        <!-- Tour Header -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Image -->
                <div class="aspect-video md:aspect-auto bg-slate-200">
                    <img src="{{ $tour->image ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80' }}" 
                         alt="{{ $tour->tour_name }}" 
                         class="w-full h-full object-cover">
                </div>
                <!-- Header Info -->
                <div class="p-8 sm:p-10 flex flex-col justify-between">
                    <div>
                        @if($tour->promo)
                            <span class="inline-block text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-4">{{ $tour->promo }}</span>
                        @endif
                        <h1 class="text-3xl sm:text-4xl font-black text-slate-900">{{ $tour->tour_name }}</h1>
                        <p class="mt-3 text-lg text-slate-500">{{ $tour->duration }} · {{ $tour->country }}</p>
                        <p class="mt-4 text-sm text-slate-400">{{ $tour->destinations }}</p>
                        
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <div class="flex items-baseline">
                                <span class="text-xs text-slate-400 uppercase tracking-wider block">From</span>
                                <span class="ml-2 font-black text-3xl text-[#216417]">₱{{ number_format($tour->price_per_pax, 2) }}<span class="text-sm font-normal text-slate-400">/pax</span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ route('contact') }}" class="inline-block px-8 py-4 bg-[#ee018d] text-white font-bold rounded-full hover:bg-pink-700 transition text-center w-full">
                            Contact Us to Book
                        </a>
                        <p class="mt-3 text-xs text-slate-400 text-center">Our travel specialist will personally guide you</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tour Details -->
        <div class="mt-12 grid md:grid-cols-3 gap-8">
            <!-- Itinerary & Details -->
            <div class="md:col-span-2 space-y-8">
                <!-- Highlights -->
                @if($tour->highlights)
                    <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100">
                        <h2 class="text-xl font-black text-slate-900 mb-4">Highlights</h2>
                        <p class="text-slate-600 leading-relaxed">{{ $tour->highlights }}</p>
                    </div>
                @endif

                <!-- Day by Day Itinerary -->
                <div class="bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100">
                    <h2 class="text-xl font-black text-slate-900 mb-6">Day by Day Itinerary</h2>
                    <div class="space-y-4">
                        @for($i = 1; $i <= 6; $i++)
                            @if($tour->{"day$i"})
                                <div class="border-l-2 border-emerald-200 pl-4">
                                    <h3 class="font-bold text-slate-900">Day {{ $i }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $tour->{"day$i"} }}</p>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>

                <!-- Inclusions & Exclusions -->
                <div class="grid sm:grid-cols-2 gap-6">
                    @if($tour->inclusions)
                        <div class="bg-white rounded-[2rem] p-6 shadow-md ring-1 ring-slate-100">
                            <h3 class="font-black text-slate-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Inclusions
                            </h3>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $tour->inclusions }}</p>
                        </div>
                    @endif
                    @if($tour->exclusions)
                        <div class="bg-white rounded-[2rem] p-6 shadow-md ring-1 ring-slate-100">
                            <h3 class="font-black text-slate-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Exclusions
                            </h3>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $tour->exclusions }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Info Sidebar -->
            <div class="space-y-6">
                <!-- Quick Details -->
                <div class="bg-white rounded-[2rem] p-6 shadow-md ring-1 ring-slate-100 sticky top-8">
                    <h3 class="font-black text-slate-900 mb-4">Quick Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">Duration</span>
                            <span class="font-semibold text-slate-900">{{ $tour->duration }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">Transport</span>
                            <span class="font-semibold text-slate-900 uppercase">{{ $tour->mode }}</span>
                        </div>
                        @if($tour->hotel)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Hotel</span>
                                <span class="font-semibold text-slate-900">{{ $tour->hotel }}</span>
                            </div>
                        @endif
                        @if($tour->meals)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Meals</span>
                                <span class="font-semibold text-slate-900">{{ $tour->meals }}</span>
                            </div>
                        @endif
                        @if($tour->hand_carry)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Hand Carry</span>
                                <span class="font-semibold text-slate-900">{{ $tour->hand_carry }}</span>
                            </div>
                        @endif
                        @if($tour->check_in_baggage)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Check-in Baggage</span>
                                <span class="font-semibold text-slate-900">{{ $tour->check_in_baggage }}</span>
                            </div>
                        @endif
                        @if($tour->tour_guide)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Tour Guide</span>
                                <span class="font-semibold text-slate-900">{{ $tour->tour_guide }}</span>
                            </div>
                        @endif
                        @if($tour->travel_insurance)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">Travel Insurance</span>
                                <span class="font-semibold text-slate-900">{{ $tour->travel_insurance }}</span>
                            </div>
                        @endif
                        @if($tour->remarks)
                            <div class="pt-3">
                                <span class="text-xs text-slate-400 block mb-1">Remarks</span>
                                <p class="text-sm text-slate-600">{{ $tour->remarks }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <a href="{{ route('contact') }}" class="inline-block px-6 py-3 bg-[#ee018d] text-white font-bold rounded-full hover:bg-pink-700 transition text-center w-full">
                            Contact Us Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
