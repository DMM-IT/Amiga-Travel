@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'domestic' }">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Tour Packages</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">Explore Our Packages</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                Affordable, reliable, and handpicked local and international tour arrangements designed for unforgettable travel memories.
            </p>

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
            <!-- Package 1: El Nido -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?auto=format&fit=crop&w=600&q=80" alt="El Nido" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2.5 py-1 rounded-full shadow-sm">Best Seller</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">El Nido Adventure</h3>
                        <p class="text-xs text-slate-400 mt-1">3 Days & 2 Nights · Inclusions: Flight + Hotel + Island Tour</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Discover limestone cliffs, crystal clear lagoons, and pristine beaches of Bacuit Bay. Includes a guided Island Tour A.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱8,999<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- Package 2: Boracay -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80" alt="Boracay" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-2.5 py-1 rounded-full shadow-sm">Popular</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Boracay Island Escape</h3>
                        <p class="text-xs text-slate-400 mt-1">3 Days & 2 Nights · Inclusions: Flight + Hotel + Transfers</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Relax on the world-famous white sand beach. Enjoy sunset paraw sailing, vibrant island nightlife, and local water sports.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱7,499<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- Package 3: Siargao -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=600&q=80" alt="Siargao" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-purple-600 uppercase tracking-widest bg-purple-50 px-2.5 py-1 rounded-full shadow-sm">Trending</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Siargao Surf & Island Tour</h3>
                        <p class="text-xs text-slate-400 mt-1">4 Days & 3 Nights · Inclusions: Hotel + Island Hopping + Surf Lesson</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Discover the surfing capital. Tour Guyam, Daku, and Naked island, followed by a professional beginner surf lesson at Cloud 9.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱9,299<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- International Packages Tab -->
        <div x-show="activeTab === 'international'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="display:none;">
            <!-- Package 1: Thailand -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=600&q=80" alt="Bangkok" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-pink-600 uppercase tracking-widest bg-pink-50 px-2.5 py-1 rounded-full shadow-sm">Fly to Bangkok</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Bangkok & Pattaya Highlights</h3>
                        <p class="text-xs text-slate-400 mt-1">4 Days & 3 Nights · Inclusions: Flight + 4★ Hotel + City Tour</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Experience majestic Buddhist temples, vibrant street food markets, and the beach resorts of Pattaya. Includes Grand Palace tour.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱18,499<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- Package 2: South Korea -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1538669715516-b2a59a7ef249?auto=format&fit=crop&w=600&q=80" alt="Seoul" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-purple-600 uppercase tracking-widest bg-purple-50 px-2.5 py-1 rounded-full shadow-sm">K-Culture Tour</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Seoul & Nami Island Experience</h3>
                        <p class="text-xs text-slate-400 mt-1">5 Days & 4 Nights · Inclusions: Flight + Hotel + Visa Assist</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Explore Gyeongbokgung Palace in traditional Hanbok clothing. Cruise to scenic Nami Island and shop in Myeongdong district.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱24,999<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- Package 3: Japan -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 flex flex-col hover:shadow-lg transition">
                <div class="aspect-video relative overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=600&q=80" alt="Kyoto" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 text-[10px] font-bold text-red-600 uppercase tracking-widest bg-red-50 px-2.5 py-1 rounded-full shadow-sm">Cherry Blossom</span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Tokyo, Kyoto & Osaka Classic</h3>
                        <p class="text-xs text-slate-400 mt-1">6 Days & 5 Nights · Inclusions: Flight + Bullet Train + Hotel</p>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            Witness the futuristic Tokyo streets, take the Shinkansen bullet train to historic Kyoto shrines, and enjoy street food in Dotonbori, Osaka.
                        </p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Starting from</span>
                            <span class="font-black text-[#216417] text-lg">₱38,999<span class="text-xs font-normal text-slate-400">/pax</span></span>
                        </div>
                        <a href="{{ url('/book/new') }}" class="px-4 py-2 bg-[#ee018d] text-white text-xs font-bold rounded-full hover:bg-pink-700 transition">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional List of Supported Destinations -->
        <div class="mt-16 bg-white rounded-[2rem] p-8 sm:p-12 shadow-md ring-1 ring-slate-100">
            <h3 class="text-xl font-bold text-slate-900 text-center mb-8">All Supported Destinations</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <h4 class="font-bold text-[#216417] text-sm uppercase tracking-wide mb-3">Southeast Asia</h4>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li>Thailand (Bangkok)</li>
                        <li>Vietnam (Hanoi/HCMC)</li>
                        <li>Singapore</li>
                        <li>Indonesia (Bali)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-[#216417] text-sm uppercase tracking-wide mb-3">East Asia</h4>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li>South Korea (Seoul)</li>
                        <li>Japan (Tokyo/Osaka)</li>
                        <li>Taiwan (Taipei)</li>
                        <li>China (Shanghai)</li>
                        <li>Hong Kong</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-[#216417] text-sm uppercase tracking-wide mb-3">Philippine Beaches</h4>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li>Puerto Galera</li>
                        <li>Boracay Island</li>
                        <li>El Nido, Palawan</li>
                        <li>Siargao Island</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-[#216417] text-sm uppercase tracking-wide mb-3">Philippine Cities</h4>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li>Cebu City</li>
                        <li>Bohol (Tagbilaran)</li>
                        <li>Manila Metro</li>
                        <li>Davao City</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
