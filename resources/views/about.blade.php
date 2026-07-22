@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">About Us</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">
                {{ $pageContent['title'] ?? 'Our Journey & Mission' }}
            </h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $pageContent['description'] ?? 'Discover the story behind Amiga Gracia Travel Services and our dedication to making every journey hassle-free and memorable.' }}
            </p>
        </div>

        <!-- History & Info Section -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div class="space-y-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                    Backed by Experience, Driven by Excellence
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Amiga Gracia was established in <strong>July 2017</strong>. Its humble beginning was born from the dedication of its founder, <strong>Mrs. MGA-Ting</strong>, whose extensive experience with 2GO laid the foundation for the company's first-class standard of service.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    What started in the municipality of Roxas, Oriental Mindoro has expanded. Following the challenges of the pandemic, our main office relocated to the thriving <strong>City of Calapan</strong>, positioned to serve travelers better than ever.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Our core ambition remains unchanged: to be recognized as the premier travel agency providing outstanding travel solutions and apprenticeship programs, both in Oriental Mindoro and nationwide.
                </p>
            </div>
            
            <div class="bg-white rounded-[2rem] p-8 shadow-xl ring-1 ring-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 h-40 w-40 bg-emerald-50 rounded-full -mr-16 -mt-16 z-0"></div>
                <div class="relative z-10 space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span> Quick Facts
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 h-10 w-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-700 font-semibold">
                                01
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Established</h4>
                                <p class="text-sm text-slate-500">July 2017 in Oriental Mindoro</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-xl flex items-center justify-center text-green-700 font-semibold">
                                02
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Key Partnerships</h4>
                                <p class="text-sm text-slate-500">2GO and Starlite Ferries</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-semibold">
                                03
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Specialty</h4>
                                <p class="text-sm text-slate-500">Ferry bookings, Educational tours, Apprenticeship programs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trusted Partners Section -->
        <div class="bg-gradient-to-br from-[#216417] to-[#14400e] text-white rounded-[2rem] p-8 sm:p-12 shadow-xl mb-16">
            <div class="max-w-3xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-4">Our Trusted Travel Operators</h3>
                <p class="text-emerald-100/90 mb-8 max-w-xl mx-auto">
                    We maintain strong, direct partnerships with major sea transit, cargo, and airline networks to bring you reliable service at competitive rates.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 items-stretch justify-items-center">
                    <div class="bg-pink-100 backdrop-blur-sm px-4 py-4 rounded-2xl w-full text-center flex items-center justify-center h-24 hover:bg-pink-200 transition shadow-sm">
                        <span class="font-black tracking-widest text-sm text-[#ee018d]">2GO TRAVEL</span>
                    </div>
                    <div class="bg-pink-100 backdrop-blur-sm px-4 py-4 rounded-2xl w-full text-center flex items-center justify-center h-24 hover:bg-pink-200 transition shadow-sm">
                        <span class="font-black tracking-widest text-sm text-[#ee018d]">STARLITE</span>
                    </div>
                    <div class="bg-pink-100 backdrop-blur-sm px-4 py-4 rounded-2xl w-full text-center flex items-center justify-center h-24 hover:bg-pink-200 transition shadow-sm">
                        <span class="font-black tracking-widest text-sm text-[#ee018d]">PHILIPPINE AIRLINES</span>
                    </div>
                    <div class="bg-pink-100 backdrop-blur-sm px-4 py-4 rounded-2xl w-full text-center flex items-center justify-center h-24 hover:bg-pink-200 transition shadow-sm">
                        <span class="font-black tracking-widest text-sm text-[#ee018d]">CEBU PACIFIC</span>
                    </div>
                    <div class="bg-pink-100 backdrop-blur-sm px-4 py-4 rounded-2xl w-full text-center flex items-center justify-center h-24 hover:bg-pink-200 transition shadow-sm">
                        <span class="font-black tracking-widest text-sm text-[#ee018d]">PHILIPPINES AIRASIA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <h3 class="text-xl font-bold text-slate-900">Kay Amiga, Hassle Free Ka!</h3>
            <p class="text-sm text-slate-500 mt-2">Ready to plan your next travel or educational tour? Let's connect.</p>
            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ url('/book/new') }}" class="px-6 py-3 bg-[#216417] text-white font-semibold rounded-full shadow-lg hover:bg-green-800 transition">
                    Book Now
                </a>
                <a href="{{ url('/contact-us') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
