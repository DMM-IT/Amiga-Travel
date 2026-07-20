@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="rounded-[2rem] bg-white p-8 shadow-xl ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Schedule</p>
                <h1 class="mt-3 text-3xl font-bold text-slate-900">Available Ferry Schedules</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">Browse active ferry routes, service times, and pricing for all available schedules.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url('/book/new') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">Book a Trip</a>
                <a href="{{ url('/tours') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-emerald-700 hover:text-emerald-700">View Tours</a>
            </div>
        </div>
    </div>

    <div class="mt-10 space-y-8">
        @forelse($routes as $route)
            <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-600">Route</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $route->origin }} → {{ $route->destination }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ $route->vehicle?->full_name ?? $route->operator ?? ucfirst($route->mode ?? 'Ferry') }}</p>
                    </div>
                    <div class="text-sm text-slate-500">
                        <p><span class="font-semibold">Mode:</span> {{ ucfirst($route->mode ?? 'Ferry') }}</p>
                        <p><span class="font-semibold">Schedules:</span> {{ $route->schedules->count() }}</p>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700">
                                <th class="px-4 py-3 font-semibold">Service</th>
                                <th class="px-4 py-3 font-semibold">Departure</th>
                                <th class="px-4 py-3 font-semibold">Arrival</th>
                                <th class="px-4 py-3 font-semibold">Duration</th>
                                <th class="px-4 py-3 font-semibold">Price</th>
                                <th class="px-4 py-3 font-semibold">Accommodations</th>
                                <th class="px-4 py-3 font-semibold">Days</th>
                                <th class="px-4 py-3 font-semibold">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($route->schedules as $schedule)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->service_name }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->formatted_departure }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->formatted_arrival }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->duration_label }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">₱{{ number_format($schedule->price, 2) }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->accommodation_label }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ implode(', ', array_map(fn($day) => $dayNames[$day] ?? $day, $schedule->operating_days ?? [])) }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-900">{{ $schedule->availability_label ?? 'Available' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-600">
                No schedules are available right now. Please check back later or contact us for the latest route information.
            </div>
        @endforelse
    </div>
</div>
@endsection
