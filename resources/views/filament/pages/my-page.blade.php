<x-filament-panels::page>
    <div class="space-y-6">
        {{-- ═══ User Profile Banner ═══ --}}
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl text-2xl font-bold text-white shadow-sm" style="background-color: #f59e0b; color: #ffffff;">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ auth()->user()?->name ?? 'Admin User' }}
                            </h2>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold" style="background-color: rgba(245, 158, 11, 0.15); color: #d97706;">
                                Staff Profile
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()?->email ?? 'admin@amigagracia.com' }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                            Member since {{ auth()->user()?->created_at?->format('F d, Y') ?? 'July 2026' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end gap-1.5">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Staff Account Active
                    </span>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Staff Performance Connected
                    </p>
                </div>
            </div>
        </div>

        {{-- ═══ Staff Performance KPI Dashboard Cards ═══ --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        My Staff Performance & Transactions
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Your personal transaction breakdown: completed, pending, and cancelled bookings
                    </p>
                </div>
                <span class="text-xs font-medium text-amber-600 dark:text-amber-400">
                    Connected to your account
                </span>
            </div>

            <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
                {{-- Total Handled --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Handled Transactions</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_transactions'] ?? 0) }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total bookings assigned to you</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Completed</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['completed'] ?? 0) }}</p>
                            <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">Successfully verified transactions</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Pending</p>
                            <p class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($stats['pending'] ?? 0) }}</p>
                            <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">Awaiting processing</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Cancelled --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Cancelled</p>
                            <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($stats['cancelled'] ?? 0) }}</p>
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">Cancelled / rejected transactions</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(239, 68, 68, 0.15); color: #ef4444;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Revenue Handled --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Revenue Handled</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($stats['revenue_handled'] ?? 0, 2) }}</p>
                            <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">Total value of completed bookings</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completion Rate --}}
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Completion Rate</p>
                            <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['completion_rate'] ?? 100 }}%</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Staff verification success rate</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg" style="background-color: rgba(99, 102, 241, 0.15); color: #6366f1;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ My Handled Transactions Table ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">My Handled Transactions</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Live record of bookings completed, pending, and cancelled under your staff account</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                    Your Latest {{ count($recentBookings) }} Transactions
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Reference</th>
                            <th class="px-6 py-3.5 font-semibold">Client</th>
                            <th class="px-6 py-3.5 font-semibold">Route</th>
                            <th class="px-6 py-3.5 font-semibold">Staff Status</th>
                            <th class="px-6 py-3.5 font-semibold">Payment</th>
                            <th class="px-6 py-3.5 text-right font-semibold">Amount</th>
                            <th class="px-6 py-3.5 text-right font-semibold">Date Handled</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white text-xs">{{ $booking['reference'] }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300 text-xs">{{ $booking['client'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-xs">{{ $booking['route'] }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $sc = match($booking['status']) {
                                            'confirmed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $sc }}">
                                        {{ ucfirst($booking['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $pc = match($booking['payment_status']) {
                                            'paid' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                            default => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $pc }}">
                                        {{ ucfirst($booking['payment_status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white tabular-nums text-sm">₱{{ number_format($booking['total_amount'], 2) }}</td>
                                <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 text-xs">{{ $booking['date'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No transactions handled under your staff profile yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══ Downloadable Reports Suite ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Downloadable CSV Reports & Data Exports
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Export your personal transaction logs or general system reports instantly to CSV.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                    Instant Download
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- My Handled Transactions --}}
                <div class="flex items-center justify-between p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg" style="background-color: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">My Handled Transactions</h4>
                                <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                    Personal Log
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Export only your completed, pending & cancelled bookings</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="downloadReport('my_transactions')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download CSV
                    </button>
                </div>

                {{-- All System Bookings --}}
                <div class="flex items-center justify-between p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg" style="background-color: rgba(168, 85, 247, 0.15); color: #a855f7;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">System Bookings</h4>
                                <span class="rounded bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    All Records
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Full reservation list across all staff members</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="downloadReport('bookings')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download CSV
                    </button>
                </div>

                {{-- Ferry & Airline Routes --}}
                <div class="flex items-center justify-between p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg" style="background-color: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Ferry & Airline Routes</h4>
                                <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                    Directory
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">All routes, operators, travel modes, and active status</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="downloadReport('ferry_routes')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download CSV
                    </button>
                </div>

                {{-- Schedules Report --}}
                <div class="flex items-center justify-between p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg" style="background-color: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Trip Schedules</h4>
                                <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Schedules
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Departure & arrival times, vehicles, and pricing</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="downloadReport('schedules')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download CSV
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
