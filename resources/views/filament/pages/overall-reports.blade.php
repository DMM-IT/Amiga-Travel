<x-filament-panels::page>
<div wire:poll.3s="refreshData" class="space-y-6">

    {{-- ═══ Header: Period Selector + Custom Dates + Export ═══ --}}
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            {{-- Period Pills --}}
            <div class="flex flex-wrap gap-2">
                @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All Time', 'custom' => 'Custom'] as $value => $label)
                    <button
                        wire:click="$set('period', '{{ $value }}')"
                        @class([
                            'rounded-lg px-4 py-2 text-sm font-semibold transition-all duration-200',
                            'bg-primary-600 text-white shadow-md' => $period === $value,
                            'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' => $period !== $value,
                        ])
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Export Buttons --}}
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('bookings.export.pdf') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <x-heroicon-m-arrow-down-tray class="h-4 w-4" />
                    PDF
                </a>
                <a href="{{ route('bookings.export.csv') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <x-heroicon-m-table-cells class="h-4 w-4" />
                    CSV
                </a>
            </div>
        </div>

        {{-- Custom Date Range --}}
        @if($period === 'custom')
            <div class="mt-4 flex flex-wrap gap-4 border-t border-gray-200 pt-4 dark:border-gray-700">
                <div class="w-48">
                    {{ $this->form }}
                </div>
            </div>
        @endif
    </div>

    {{-- ═══ KPI Cards ═══ --}}
    @php
        $bookingTrend = ($stats['prev_total_bookings'] ?? 0) > 0
            ? round((($stats['total_bookings'] - $stats['prev_total_bookings']) / $stats['prev_total_bookings']) * 100, 1)
            : ($stats['total_bookings'] > 0 ? 100 : 0);
        $revenueTrend = ($stats['prev_total_revenue'] ?? 0) > 0
            ? round((($stats['total_revenue'] - $stats['prev_total_revenue']) / $stats['prev_total_revenue']) * 100, 1)
            : ($stats['total_revenue'] > 0 ? 100 : 0);
    @endphp
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        {{-- Total Bookings --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-ticket class="h-4 w-4 text-blue-500" />
                Total Bookings
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ number_format($stats['total_bookings'] ?? 0) }}</p>
            <div class="mt-1 flex items-center gap-1 text-xs font-medium {{ $bookingTrend >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                @if($bookingTrend >= 0)
                    <x-heroicon-m-arrow-trending-up class="h-3.5 w-3.5" />
                @else
                    <x-heroicon-m-arrow-trending-down class="h-3.5 w-3.5" />
                @endif
                {{ abs($bookingTrend) }}% vs prev period
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-banknotes class="h-4 w-4 text-emerald-500" />
                Total Revenue
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
            <div class="mt-1 flex items-center gap-1 text-xs font-medium {{ $revenueTrend >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                @if($revenueTrend >= 0)
                    <x-heroicon-m-arrow-trending-up class="h-3.5 w-3.5" />
                @else
                    <x-heroicon-m-arrow-trending-down class="h-3.5 w-3.5" />
                @endif
                {{ abs($revenueTrend) }}% vs prev period
            </div>
        </div>

        {{-- Avg Booking Value --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-calculator class="h-4 w-4 text-violet-500" />
                Avg Booking Value
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">₱{{ number_format($stats['avg_booking_value'] ?? 0, 2) }}</p>
            <p class="mt-1 text-xs text-gray-400">Per booking</p>
        </div>

        {{-- Completion Rate --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-check-circle class="h-4 w-4 text-emerald-500" />
                Completion Rate
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ $stats['completion_rate'] ?? 0 }}%</p>
            <p class="mt-1 text-xs text-gray-400">{{ $stats['completed_bookings'] ?? 0 }} confirmed</p>
        </div>

        {{-- Cancellation Rate --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-x-circle class="h-4 w-4 text-red-500" />
                Cancellation Rate
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ $stats['cancellation_rate'] ?? 0 }}%</p>
            <p class="mt-1 text-xs text-gray-400">{{ $stats['cancelled_bookings'] ?? 0 }} cancelled</p>
        </div>

        {{-- Rebookings --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <x-heroicon-o-arrow-path class="h-4 w-4 text-amber-500" />
                Rebookings
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ number_format($stats['rebooking_count'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">₱{{ number_format($stats['pending_revenue'] ?? 0, 0) }} pending</p>
        </div>
    </div>

    {{-- ═══ Charts Row 1: Revenue + Booking Volume ═══ --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-1">Revenue Trend</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Revenue over time for the selected period</p>
            <div wire:ignore id="report-revenue-chart" style="height: 320px;"></div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-1">Booking Volume</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Number of bookings over time</p>
            <div wire:ignore id="report-booking-volume-chart" style="height: 320px;"></div>
        </div>
    </div>

    {{-- ═══ Charts Row 2: Status Distribution + Transport Mode + Top Routes ═══ --}}
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Status Distribution</h3>
            <div wire:ignore id="report-status-chart" style="height: 280px;"></div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Transport Mode</h3>
            <div wire:ignore id="report-mode-chart" style="height: 280px;"></div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Top Routes by Revenue</h3>
            <div wire:ignore id="report-routes-chart" style="height: 280px;"></div>
        </div>
    </div>

    {{-- ═══ Tables Row: Recent Bookings + Transactions ═══ --}}
    <div class="grid gap-6 xl:grid-cols-2">
        {{-- Recent Bookings --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-1">Recent Bookings</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Latest bookings for the selected period</p>
            <div class="overflow-x-auto rounded-lg ring-1 ring-gray-200 dark:ring-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Transaction</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Client</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Route</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 font-mono text-xs">{{ $booking['transaction_number'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $booking['client_name'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $booking['route'] }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $sc = match($booking['status']) {
                                            'confirmed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold {{ $sc }}">
                                        {{ ucfirst($booking['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white tabular-nums">₱{{ number_format($booking['total_price'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">No bookings found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-1">Recent Transactions</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Latest payments and rebooking transactions</p>
            <div class="overflow-x-auto rounded-lg ring-1 ring-gray-200 dark:ring-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Transaction</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Client</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Proof</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 font-mono text-xs">{{ $transaction['transaction_number'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $transaction['client_name'] }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $tc = match($transaction['payment_status']) {
                                            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                            default => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold {{ $tc }}">
                                        {{ ucfirst($transaction['payment_status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($transaction['proof_uploaded'])
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                            <x-heroicon-m-check-circle class="h-3.5 w-3.5" /> Uploaded
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400 text-xs">{{ $transaction['created_at'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">No transactions found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══ Insights Row: Passengers + Staff + Tours ═══ --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Passenger Demographics --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Passenger Demographics</h3>
            <div wire:ignore id="report-passenger-chart" style="height: 250px;"></div>
        </div>

        {{-- Staff Leaderboard --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Staff Leaderboard</h3>
            <div class="space-y-3">
                @forelse($staffLeaderboard as $index => $staff)
                    <div class="flex items-center gap-3">
                        <span @class([
                            'flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold',
                            'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' => $index === 0,
                            'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' => $index > 0,
                        ])>
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $staff['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $staff['verifications'] }} verifications · ₱{{ number_format($staff['revenue'], 0) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No staff activity in this period</p>
                @endforelse
            </div>
        </div>

        {{-- Tour Performance --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Tour Performance</h3>
            <div class="space-y-3">
                @forelse($tourPerformance as $tour)
                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $tour['name'] }}</p>
                        <div class="mt-1.5 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <x-heroicon-m-ticket class="h-3.5 w-3.5" />
                                {{ $tour['bookings'] }} bookings
                            </span>
                            <span class="flex items-center gap-1 font-medium text-emerald-600 dark:text-emerald-400">
                                ₱{{ number_format($tour['revenue'], 0) }}
                            </span>
                            @if($tour['upcoming_dates'] > 0)
                                <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                    <x-heroicon-m-calendar class="h-3.5 w-3.5" />
                                    {{ $tour['upcoming_dates'] }} upcoming
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No active tours</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══ Payment Analytics Row ═══ --}}
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h3 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Payment Analytics</h3>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $paymentAnalytics['paid'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Paid</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $paymentAnalytics['pending'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Pending</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $paymentAnalytics['failed'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Failed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $paymentAnalytics['total'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Transactions</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $paymentAnalytics['proof_upload_rate'] ?? 0 }}%</p>
                <p class="text-xs text-gray-500 mt-1">Proof Upload Rate</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══ ApexCharts Initialization & Update Script ═══ --}}
@script
<script>
const reportCharts = {};

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function baseTheme() {
    const dark = isDark();
    return {
        theme: { mode: dark ? 'dark' : 'light' },
        chart: { background: 'transparent', fontFamily: 'inherit', toolbar: { show: false } },
        grid: { borderColor: dark ? '#374151' : '#e5e7eb', strokeDashArray: 4 },
        tooltip: { theme: dark ? 'dark' : 'light' },
        xaxis: { labels: { style: { colors: dark ? '#9ca3af' : '#6b7280', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { style: { colors: dark ? '#9ca3af' : '#6b7280', fontSize: '11px' } } },
    };
}

function initReportCharts(data) {
    const bt = baseTheme();
    const dark = isDark();

    // Revenue Area Chart
    const revEl = document.getElementById('report-revenue-chart');
    if (revEl) {
        reportCharts.revenue = new ApexCharts(revEl, {
            ...bt,
            chart: { ...bt.chart, type: 'area', height: 320, animations: { enabled: true, easing: 'easeinout', speed: 500 } },
            series: data.revenue?.series || [],
            xaxis: { ...bt.xaxis, categories: data.revenue?.categories || [], tickAmount: 8 },
            yaxis: { ...bt.yaxis, labels: { ...bt.yaxis.labels, formatter: (v) => '₱' + (v || 0).toLocaleString() } },
            colors: ['#f59e0b'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
            stroke: { curve: 'smooth', width: 2.5 },
            dataLabels: { enabled: false },
            tooltip: { ...bt.tooltip, y: { formatter: (v) => '₱' + (v || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) } },
        });
        reportCharts.revenue.render();
    }

    // Booking Volume Bar Chart
    const volEl = document.getElementById('report-booking-volume-chart');
    if (volEl) {
        reportCharts.bookingVolume = new ApexCharts(volEl, {
            ...bt,
            chart: { ...bt.chart, type: 'bar', height: 320, animations: { enabled: true, easing: 'easeinout', speed: 500 } },
            series: data.bookingVolume?.series || [],
            xaxis: { ...bt.xaxis, categories: data.bookingVolume?.categories || [], tickAmount: 8 },
            colors: ['#3b82f6'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
            dataLabels: { enabled: false },
        });
        reportCharts.bookingVolume.render();
    }

    // Status Donut
    const statEl = document.getElementById('report-status-chart');
    if (statEl) {
        reportCharts.status = new ApexCharts(statEl, {
            ...bt,
            chart: { ...bt.chart, type: 'donut', height: 280 },
            series: data.statusDistribution?.series || [0, 0, 0],
            labels: data.statusDistribution?.labels || ['Confirmed', 'Pending', 'Cancelled'],
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            plotOptions: { pie: { donut: { size: '70%', labels: { show: true, name: { color: dark ? '#fff' : '#1f2937' }, value: { color: dark ? '#fff' : '#1f2937', fontSize: '22px', fontWeight: 700 }, total: { show: true, label: 'Total', color: dark ? '#9ca3af' : '#6b7280', formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
            stroke: { width: 2, colors: [dark ? '#111827' : '#fff'] },
            legend: { position: 'bottom', labels: { colors: dark ? '#d1d5db' : '#374151' }, fontSize: '12px', markers: { size: 8, shape: 'circle' }, itemMargin: { horizontal: 10, vertical: 4 } },
            dataLabels: { enabled: false },
        });
        reportCharts.status.render();
    }

    // Transport Mode Pie
    const modeEl = document.getElementById('report-mode-chart');
    if (modeEl) {
        reportCharts.mode = new ApexCharts(modeEl, {
            ...bt,
            chart: { ...bt.chart, type: 'pie', height: 280 },
            series: data.transportMode?.series || [0],
            labels: data.transportMode?.labels || ['No Data'],
            colors: ['#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b'],
            stroke: { width: 2, colors: [dark ? '#111827' : '#fff'] },
            legend: { position: 'bottom', labels: { colors: dark ? '#d1d5db' : '#374151' }, fontSize: '12px', markers: { size: 8, shape: 'circle' }, itemMargin: { horizontal: 10, vertical: 4 } },
            dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 600 }, dropShadow: { enabled: false } },
        });
        reportCharts.mode.render();
    }

    // Top Routes Horizontal Bar
    const routeEl = document.getElementById('report-routes-chart');
    if (routeEl) {
        reportCharts.routes = new ApexCharts(routeEl, {
            ...bt,
            chart: { ...bt.chart, type: 'bar', height: 280 },
            series: data.topRoutes?.series || [],
            xaxis: { ...bt.xaxis, categories: data.topRoutes?.categories || [], labels: { ...bt.xaxis.labels, formatter: (v) => '₱' + (v || 0).toLocaleString() } },
            yaxis: { ...bt.yaxis, labels: { ...bt.yaxis.labels, style: { ...bt.yaxis.labels.style, fontSize: '10px' }, maxWidth: 150 } },
            colors: ['#f59e0b'],
            plotOptions: { bar: { borderRadius: 4, horizontal: true, barHeight: '60%' } },
            dataLabels: { enabled: false },
            tooltip: { ...bt.tooltip, y: { formatter: (v) => '₱' + (v || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) } },
        });
        reportCharts.routes.render();
    }

    // Passenger Demographics Bar
    const passEl = document.getElementById('report-passenger-chart');
    if (passEl) {
        reportCharts.passengers = new ApexCharts(passEl, {
            ...bt,
            chart: { ...bt.chart, type: 'bar', height: 250 },
            series: data.passengers?.series || [],
            xaxis: { ...bt.xaxis, categories: data.passengers?.categories || [] },
            colors: ['#8b5cf6'],
            plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
            dataLabels: { enabled: false },
        });
        reportCharts.passengers.render();
    }
}

function updateReportCharts(data) {
    if (reportCharts.revenue && data.revenue) {
        reportCharts.revenue.updateOptions({ xaxis: { categories: data.revenue.categories } }, false, false);
        reportCharts.revenue.updateSeries(data.revenue.series);
    }
    if (reportCharts.bookingVolume && data.bookingVolume) {
        reportCharts.bookingVolume.updateOptions({ xaxis: { categories: data.bookingVolume.categories } }, false, false);
        reportCharts.bookingVolume.updateSeries(data.bookingVolume.series);
    }
    if (reportCharts.status && data.statusDistribution) {
        reportCharts.status.updateOptions({ labels: data.statusDistribution.labels }, false, false);
        reportCharts.status.updateSeries(data.statusDistribution.series);
    }
    if (reportCharts.mode && data.transportMode) {
        reportCharts.mode.updateOptions({ labels: data.transportMode.labels }, false, false);
        reportCharts.mode.updateSeries(data.transportMode.series);
    }
    if (reportCharts.routes && data.topRoutes) {
        reportCharts.routes.updateOptions({ xaxis: { categories: data.topRoutes.categories } }, false, false);
        reportCharts.routes.updateSeries(data.topRoutes.series);
    }
    if (reportCharts.passengers && data.passengers) {
        reportCharts.passengers.updateOptions({ xaxis: { categories: data.passengers.categories } }, false, false);
        reportCharts.passengers.updateSeries(data.passengers.series);
    }
}

// Initialize charts on load
initReportCharts(@js($chartData));

// Listen for Livewire updates
$wire.on('report-charts-updated', ({ chartData }) => {
    if (chartData) updateReportCharts(chartData);
});

// Dark mode observer
const darkObserver = new MutationObserver(() => {
    // Destroy and re-init all charts on theme change for clean re-render
    Object.values(reportCharts).forEach(c => c?.destroy());
    Object.keys(reportCharts).forEach(k => delete reportCharts[k]);
    // Re-fetch current data from Livewire
    const data = @js($chartData);
    initReportCharts(data);
});
darkObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>
@endscript
</x-filament-panels::page>
