@php use Filament\Support\Enums\MaxWidth; @endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Period Selector -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Reporting Period</h2>
                <div class="flex gap-2">
                    @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All Time'] as $value => $label)
                        <button
                            wire:click="$set('period', '{{ $value }}')"
                            @class([
                                'rounded-lg px-4 py-2 font-semibold transition text-sm',
                                'bg-amber-500 text-white' => $period === $value,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' => $period !== $value,
                            ])
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Bookings -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bookings</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_bookings'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Bookings -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['completed_bookings'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-emerald-100 p-3 dark:bg-emerald-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Bookings -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_bookings'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-amber-100 p-3 dark:bg-amber-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Booking Status -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Booking Status Distribution</h3>
                <div class="space-y-4">
                    @php
                        $statuses = [
                            'pending' => ['label' => 'Pending', 'color' => 'bg-amber-100', 'textColor' => 'text-amber-700'],
                            'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-emerald-100', 'textColor' => 'text-emerald-700'],
                            'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-red-100', 'textColor' => 'text-red-700'],
                        ];
                        $total = $breakdown['pending'] + $breakdown['confirmed'] + $breakdown['cancelled'];
                    @endphp
                    
                    @foreach($statuses as $status => $config)
                        @php
                            $count = $breakdown[$status] ?? 0;
                            $progressWidth = number_format($total > 0 ? ($count / $total) * 100 : 0, 1);
                        @endphp
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $config['label'] }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }} ({{ $progressWidth }}%)</span>
                            </div>
                            <div class="overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="{{ $config['color'] }} h-2 transition-all status-progress-bar" data-progress-width="{{ $progressWidth }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.status-progress-bar').forEach(function (bar) {
                        var width = bar.getAttribute('data-progress-width');
                        if (width) {
                            bar.style.width = width + '%';
                        }
                    });
                });
            </script>

            <!-- Additional Metrics -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Additional Metrics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Cancelled Bookings</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['cancelled_bookings'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Rebooking Requests</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['rebooking_count'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Pending Revenue</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₱{{ number_format($stats['pending_revenue'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Cancellation Fees Collected</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₱{{ number_format($stats['cancelled_revenue'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Exports -->
        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Bookings</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Last 5 bookings created in the system.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('bookings.export.pdf') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Download PDF
                        </a>
                        <a href="{{ route('bookings.export.csv') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                            Download CSV
                        </a>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Transaction</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Client</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Route</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Dates</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $booking['transaction_number'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $booking['client_name'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $booking['route'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $booking['travel_dates'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $booking['status'] }}</td>
                                    <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">{{ $booking['total_price'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No recent bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Last 5 payments and rebooking transactions.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Transaction</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Rebooking Fee</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Proof</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $transaction['transaction_number'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $transaction['payment_status'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $transaction['rebooking_fee'] }}</td>
                                    <td class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">{{ $transaction['proof_uploaded'] }}</td>
                                    <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">{{ $transaction['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No recent transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
