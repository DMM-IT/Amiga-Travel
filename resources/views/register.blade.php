@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="rounded-[2rem] bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-8 sm:px-10 text-center" style="background: linear-gradient(135deg, #216417 0%, #14400e 100%);">
                <h1 class="text-2xl font-semibold text-white">Create account</h1>
                <p class="mt-2 text-sm text-white/85">Register to track your bookings in one place.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="p-6 sm:p-10 space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20 @error('name') border-red-400 @enderror"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20 @error('email') border-red-400 @enderror"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20 @error('password') border-red-400 @enderror"
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-[#216417] focus:outline-none focus:ring-2 focus:ring-[#216417]/20"
                    />
                </div>

                <button type="submit" class="w-full rounded-3xl px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background:#216417;">
                    Create account
                </button>
            </form>

            <div class="px-6 pb-8 sm:px-10 text-center text-sm text-slate-600 space-y-2">
                <p>
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold" style="color:#216417;">Sign in</a>
                </p>
                <p>
                    <a href="{{ route('book') }}" class="text-slate-500 hover:text-slate-700">← Back to booking home</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
