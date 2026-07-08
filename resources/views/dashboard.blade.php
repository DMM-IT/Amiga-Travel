@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">Welcome back, {{ auth()->user()->name }}</h1>
                    <p class="mt-2 text-slate-600">Your bookings and payment status are below.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ url('/book/new') }}" class="inline-flex items-center justify-center rounded-3xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Book a new trip</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        @livewire('user-dashboard')
    </div>
</div>
@endsection
