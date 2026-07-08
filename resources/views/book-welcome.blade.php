@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-3xl">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-10 sm:px-12 sm:py-14 text-center" style="background: linear-gradient(135deg, #216417 0%, #14400e 100%);">
                <img src="{{ asset('images/amiga-logo.jpg') }}" alt="Amiga Gracia Travel Services" class="mx-auto h-20 sm:h-24 w-auto rounded-2xl bg-white p-2 shadow-md" />
                <h1 class="mt-6 text-2xl sm:text-3xl font-semibold text-white">Welcome to Amiga Gracia Travel Services</h1>
                <p class="mt-3 text-white/85 max-w-lg mx-auto">Ferry bookings, accommodations, and everything in between — made easy. What would you like to do today?</p>
            </div>

            <div class="p-6 sm:p-10 grid gap-5 sm:grid-cols-2">
                <a href="{{ url('/book/new') }}" class="group rounded-3xl border-2 border-slate-200 p-6 text-left transition duration-200 hover:shadow-md" style="--hover-border: #216417;" onmouseover="this.style.borderColor='#216417'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl" style="background:#eaf5e8;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color:#216417;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-lg font-semibold text-slate-900">Book a Trip</h2>
                    <p class="mt-2 text-sm text-slate-600">Start a new booking — choose your route, schedule, passengers, and accommodations.</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold" style="color:#216417;">Get started →</span>
                </a>

                <a href="{{ url('/book/status') }}" class="group rounded-3xl border-2 border-slate-200 p-6 text-left transition duration-200 hover:shadow-md" onmouseover="this.style.borderColor='#ee018d'" onmouseout="this.style.borderColor=''">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl" style="background:#fde6f3;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color:#ee018d;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.2 4.2a7.5 7.5 0 0012.45 12.45z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-lg font-semibold text-slate-900">Check My Booking</h2>
                    <p class="mt-2 text-sm text-slate-600">Already booked? Enter your transaction number to view your booking details and status.</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold" style="color:#ee018d;">Check status →</span>
                </a>
            </div>

            <div class="px-6 pb-8 sm:px-10 text-center">
                <p class="text-xs text-slate-400">Amiga Gracia Travel Services · travel-2go.com.ph</p>
            </div>
        </div>
    </div>
</div>
@endsection
