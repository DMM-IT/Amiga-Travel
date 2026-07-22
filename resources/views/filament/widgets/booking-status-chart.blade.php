<div>
    <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">Booking Status</h3>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">All Time</span>
        </div>
        <div wire:ignore>
            <div id="dashboard-booking-status-chart" style="height: 280px;"></div>
        </div>
    </div>

    @script
    <script>
        let bookingStatusChart = null;

        function getStatusOptions(data) {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'inherit',
                    background: 'transparent',
                    animations: { enabled: true, easing: 'easeinout', speed: 600 },
                },
                series: data.series || [0, 0, 0],
                labels: data.labels || ['Confirmed', 'Pending', 'Cancelled'],
                colors: ['#10b981', '#f59e0b', '#ef4444'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '14px', color: isDark ? '#fff' : '#1f2937' },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: isDark ? '#fff' : '#1f2937',
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '13px',
                                    color: isDark ? '#9ca3af' : '#6b7280',
                                    formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0),
                                },
                            },
                        },
                    },
                },
                stroke: { width: 2, colors: [isDark ? '#111827' : '#ffffff'] },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '13px',
                    labels: { colors: isDark ? '#d1d5db' : '#374151' },
                    markers: { size: 8, shape: 'circle', offsetX: -4 },
                    itemMargin: { horizontal: 12, vertical: 4 },
                },
                dataLabels: { enabled: false },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: { formatter: (val) => val + ' bookings' },
                },
                theme: { mode: isDark ? 'dark' : 'light' },
            };
        }

        const initData = @js($chartData);
        const el = document.getElementById('dashboard-booking-status-chart');
        if (el) {
            bookingStatusChart = new ApexCharts(el, getStatusOptions(initData));
            bookingStatusChart.render();
        }

        $wire.on('booking-status-chart-updated', ({ chartData }) => {
            if (bookingStatusChart && chartData) {
                bookingStatusChart.updateOptions({ labels: chartData.labels || [] }, false, false);
                bookingStatusChart.updateSeries(chartData.series || []);
            }
        });

        const statusObserver = new MutationObserver(() => {
            if (bookingStatusChart) {
                const isDark = document.documentElement.classList.contains('dark');
                bookingStatusChart.updateOptions({
                    theme: { mode: isDark ? 'dark' : 'light' },
                    stroke: { colors: [isDark ? '#111827' : '#ffffff'] },
                    legend: { labels: { colors: isDark ? '#d1d5db' : '#374151' } },
                    tooltip: { theme: isDark ? 'dark' : 'light' },
                }, false, false);
            }
        });
        statusObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    </script>
    @endscript
</div>
