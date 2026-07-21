@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<div class="relative bg-gradient-to-br from-[#216417] via-[#1a5212] to-[#0e3b0a] overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="absolute top-0 right-0 w-[600px] h-[600px] -translate-y-1/4 translate-x-1/4 text-white" viewBox="0 0 200 200" fill="currentColor">
            <circle cx="100" cy="100" r="100" opacity="0.08"/>
        </svg>
        <svg class="absolute bottom-0 left-0 w-[400px] h-[400px] translate-y-1/4 -translate-x-1/4 text-white" viewBox="0 0 200 200" fill="currentColor">
            <circle cx="100" cy="100" r="80" opacity="0.06"/>
        </svg>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-4 py-1.5 mb-4">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium text-emerald-100">Real-time schedules</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">
                    Schedule and Routes
                </h1>
                <p class="mt-2 text-xl font-medium text-emerald-100">
                    for {{ \Carbon\Carbon::parse($startDate)->format('F j') }} - {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}
                </p>
                <p class="mt-3 text-base sm:text-lg text-emerald-100/80 max-w-xl">
                    Browse available ferry and airline routes with live pricing, departure times, and accommodation options.
                </p>
            </div>

        </div>

        {{-- Quick Stats --}}
        <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $totalRoutes = $routes->count();
                $totalSchedules = $routes->sum(fn($r) => $r->schedules->count());
                $ferryRoutes = $routes->where('mode', 'ferry')->count() + $routes->whereNull('mode')->count();
                $airlineRoutes = $routes->where('mode', 'airline')->count();
            @endphp
            <div class="rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 px-4 py-3">
                <p class="text-2xl font-bold text-white">{{ $totalRoutes }}</p>
                <p class="text-xs text-emerald-200/70 font-medium mt-0.5">Active Routes</p>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 px-4 py-3">
                <p class="text-2xl font-bold text-white">{{ $totalSchedules }}</p>
                <p class="text-xs text-emerald-200/70 font-medium mt-0.5">Daily Departures</p>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 px-4 py-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
                    <p class="text-2xl font-bold text-white">{{ $ferryRoutes }}</p>
                </div>
                <p class="text-xs text-emerald-200/70 font-medium mt-0.5">Ferry Routes</p>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 px-4 py-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    <p class="text-2xl font-bold text-white">{{ $airlineRoutes }}</p>
                </div>
                <p class="text-xs text-emerald-200/70 font-medium mt-0.5">Airline Routes</p>
            </div>
        </div>
    </div>
</div>

{{-- Search + Filter --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10" x-data="{
    activeFilter: 'all',
    selectedOrigin: '',
    selectedDestination: '',
    swapRoute() {
        let tmp = this.selectedOrigin;
        this.selectedOrigin = this.selectedDestination;
        this.selectedDestination = tmp;
    },
    matchesSearch(origin, destination) {
        if (this.selectedOrigin && origin !== this.selectedOrigin) return false;
        if (this.selectedDestination && destination !== this.selectedDestination) return false;
        return true;
    },
    matchesMode(mode) {
        return this.activeFilter === 'all' || this.activeFilter === mode;
    }
}">
    {{-- Origin / Destination Search Bar --}}
    @php
        $origins = $routes->pluck('origin')->unique()->sort()->values();
        $destinations = $routes->pluck('destination')->unique()->sort()->values();
    @endphp
    <form action="{{ route('schedules') }}" method="GET" class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden mb-4 p-4 flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-[#216417] focus:border-[#216417]">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-[#216417] focus:border-[#216417]">
        </div>
        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-[#216417] hover:bg-[#1a5212] text-white font-semibold rounded-xl text-sm transition">
            Apply Dates
        </button>
    </form>

    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden mb-4">
        <div class="h-1 bg-gradient-to-r from-[#216417] via-emerald-400 to-[#216417]"></div>
        <div class="flex flex-col sm:flex-row items-stretch">
            {{-- Origin --}}
            <div class="flex-1 relative">
                <label class="absolute top-3 left-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Origin</label>
                <select x-model="selectedOrigin" class="w-full h-full pt-8 pb-3 px-5 text-sm font-medium text-slate-700 bg-transparent border-0 focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                    <option value="">All Origins</option>
                    @foreach($origins as $origin)
                        <option value="{{ $origin }}">{{ $origin }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                </div>
            </div>

            {{-- Swap Button --}}
            <div class="flex items-center justify-center px-1 sm:px-0">
                <button @click="swapRoute()" type="button" class="group flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-400 hover:border-[#216417] hover:text-[#216417] hover:bg-emerald-50 transition-all duration-200 shadow-sm hover:shadow" title="Swap origin and destination">
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                </button>
            </div>

            {{-- Destination --}}
            <div class="flex-1 relative border-t sm:border-t-0 sm:border-l border-slate-100">
                <label class="absolute top-3 left-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Destination</label>
                <select x-model="selectedDestination" class="w-full h-full pt-8 pb-3 px-5 text-sm font-medium text-slate-700 bg-transparent border-0 focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                    <option value="">Where Are You Headed?</option>
                    @foreach($destinations as $dest)
                        <option value="{{ $dest }}">{{ $dest }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Mode Filter Tabs --}}
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-2 flex flex-wrap gap-2">
        <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-[#216417] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 sm:flex-none rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200 min-w-[100px]">
            All Routes
        </button>
        @if($ferryRoutes > 0)
        <button @click="activeFilter = 'ferry'" :class="activeFilter === 'ferry' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 sm:flex-none rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 min-w-[100px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
            Ferry
        </button>
        @endif
        @if($airlineRoutes > 0)
        <button @click="activeFilter = 'airline'" :class="activeFilter === 'airline' ? 'bg-amber-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 sm:flex-none rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 min-w-[100px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            Airline
        </button>
        @endif

        {{-- Route Cards --}}
        <div class="w-full mt-4 space-y-6">
            @forelse($routes as $route)
                @php
                    $routeMode = $route->mode ?? 'ferry';
                    $isFerry = $routeMode !== 'airline';
                    $modeColor = $isFerry ? 'blue' : 'amber';
                    $modeIcon = $isFerry ? 'ferry' : 'airline';
                @endphp
                <div
                    x-show="matchesMode('{{ $routeMode }}') && matchesSearch('{{ $route->origin }}', '{{ $route->destination }}')"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition-shadow duration-300"
                >
                    {{-- Route Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                {{-- Mode Badge --}}
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $isFerry ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                                    @if($isFerry)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-3">
                                        <h2 class="text-xl font-bold text-slate-900">{{ $route->origin }}</h2>
                                        <div class="flex items-center gap-1 text-slate-400">
                                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                            <div class="w-8 h-px bg-slate-300"></div>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                            <div class="w-8 h-px bg-slate-300"></div>
                                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                        </div>
                                        <h2 class="text-xl font-bold text-slate-900">{{ $route->destination }}</h2>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $isFerry ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ ucfirst($routeMode) }}
                                        </span>
                                        <span class="text-sm text-slate-500">{{ $route->vehicle?->full_name ?? $route->operator ?? '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-xs text-slate-500 font-medium">{{ $route->schedules->count() }} {{ Str::plural('departure', $route->schedules->count()) }}</p>
                                    @if($route->schedules->count() > 0)
                                        <p class="text-sm font-semibold text-[#216417]">From ₱{{ number_format($route->schedules->min('price'), 0) }}</p>
                                    @endif
                                </div>
                                <a href="{{ url('/book/new') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl bg-[#216417] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1a5212] hover:shadow-md">
                                    Book Now
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Schedule Cards Grid --}}
                    <div class="p-4 sm:p-6">
                        @if($route->schedules->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($route->schedules as $schedule)
                                    <div class="group relative rounded-xl border border-slate-200 bg-white p-4 transition-all duration-200 hover:border-[#216417]/30 hover:shadow-md">
                                        {{-- Service Name & Time --}}
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $schedule->service_name }}</h3>
                                                @if($schedule->vehicle_name)
                                                    <p class="text-xs text-slate-500 mt-0.5">{{ $schedule->vehicle_name }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-sm font-bold text-emerald-700">
                                                ₱{{ number_format($schedule->price, 0) }}
                                            </span>
                                        </div>

                                        {{-- Time Bar --}}
                                        <div class="flex items-center gap-3 py-3 border-t border-b border-slate-100">
                                            <div class="text-center">
                                                <p class="text-lg font-bold text-slate-900 leading-none">{{ $schedule->formatted_departure }}</p>
                                                <p class="text-[10px] uppercase tracking-wider text-slate-400 mt-1">Depart</p>
                                            </div>
                                            <div class="flex-1 flex flex-col items-center">
                                                <p class="text-[10px] font-medium text-slate-400 mb-1">{{ $schedule->duration_label }}</p>
                                                <div class="relative w-full h-px bg-slate-200">
                                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-[#216417]"></div>
                                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-[#216417]"></div>
                                                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                                        @if($isFerry)
                                                            <svg class="w-3.5 h-3.5 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6-3 6 3 6-3v13l-6 3-6-3-6 3V7z"/></svg>
                                                        @else
                                                            <svg class="w-3.5 h-3.5 text-[#216417]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-lg font-bold text-slate-900 leading-none">{{ $schedule->formatted_arrival }}</p>
                                                <p class="text-[10px] uppercase tracking-wider text-slate-400 mt-1">Arrive</p>
                                            </div>
                                        </div>

                                        {{-- Details --}}
                                        <div class="mt-3 space-y-2">
                                            {{-- Departure Date --}}
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <p class="text-xs font-semibold text-slate-700">
                                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('l, F j, Y') }}
                                                </p>
                                            </div>

                                            {{-- Accommodation / Classes --}}
                                            <div class="flex items-start gap-2">
                                                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                <p class="text-xs text-slate-600 leading-relaxed">{{ $schedule->accommodation_label }}</p>
                                            </div>

                                            {{-- Availability --}}
                                            @if($schedule->availability_label && $schedule->availability_label !== 'Available')
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    <p class="text-xs font-medium text-amber-600">{{ $schedule->availability_label }}</p>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <p class="text-xs font-medium text-emerald-600">Available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Mobile Book Button --}}
                            <div class="mt-4 sm:hidden">
                                <a href="{{ url('/book/new') }}" class="flex items-center justify-center gap-2 rounded-xl bg-[#216417] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1a5212]">
                                    Book {{ $route->origin }} → {{ $route->destination }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm font-medium">No active schedules for this route</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-16 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-lg font-bold text-slate-600 mb-2">No Schedules Available</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto">No active schedules at the moment. Please check back later or contact us for the latest route information.</p>
                    <a href="{{ url('/contact-us') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#216417] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#1a5212]">
                        Contact Us
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Bottom CTA --}}
@if($routes->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-4">
    <div class="rounded-2xl bg-gradient-to-r from-[#216417] to-[#1a5212] p-8 sm:p-10 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute -right-10 -top-10 w-48 h-48 text-white" viewBox="0 0 200 200" fill="currentColor"><circle cx="100" cy="100" r="100"/></svg>
            <svg class="absolute -left-10 -bottom-10 w-36 h-36 text-white" viewBox="0 0 200 200" fill="currentColor"><circle cx="100" cy="100" r="100"/></svg>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">Ready to Travel?</h2>
            <p class="mt-2 text-emerald-100/80 max-w-lg mx-auto">Book your ferry or airline ticket in just a few easy steps. Best rates guaranteed.</p>
            <div class="mt-6 flex flex-wrap gap-3 justify-center">
                <a href="{{ url('/book/new') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-[#216417] shadow-lg transition hover:bg-emerald-50 hover:shadow-xl">
                    Start Booking
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ url('/contact-us') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-8 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">
                    Need Help?
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
