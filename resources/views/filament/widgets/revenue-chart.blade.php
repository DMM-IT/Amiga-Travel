<div>
    <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">Revenue Trend</h3>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Last 30 days</span>
        </div>
        <div wire:ignore>
            <div id="dashboard-revenue-chart" style="height: 280px;"></div>
        </div>
    </div>

    @script
    <script>
        let revenueChart = null;

        function getRevenueOptions(data) {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                chart: {
                    type: 'area',
                    height: 280,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    background: 'transparent',
                    animations: { enabled: true, easing: 'easeinout', speed: 600 },
                },
                series: data.series || [],
                xaxis: {
                    categories: data.categories || [],
                    labels: {
                        style: { colors: isDark ? '#9ca3af' : '#6b7280', fontSize: '11px' },
                        rotate: -45,
                        rotateAlways: false,
                        hideOverlappingLabels: true,
                        trim: true,
                        maxHeight: 60,
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tickAmount: 8,
                },
                yaxis: {
                    labels: {
                        style: { colors: isDark ? '#9ca3af' : '#6b7280', fontSize: '11px' },
                        formatter: (val) => '₱' + val.toLocaleString(),
                    },
                },
                colors: ['#f59e0b'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] },
                },
                stroke: { curve: 'smooth', width: 2.5 },
                grid: {
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    strokeDashArray: 4,
                    padding: { left: 8, right: 8 },
                },
                dataLabels: { enabled: false },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: { formatter: (val) => '₱' + val.toLocaleString(undefined, { minimumFractionDigits: 2 }) },
                },
                theme: { mode: isDark ? 'dark' : 'light' },
            };
        }

        function initRevenueChart(data) {
            const el = document.getElementById('dashboard-revenue-chart');
            if (el && window.ApexCharts) {
                if (revenueChart) {
                    revenueChart.destroy();
                }
                revenueChart = new ApexCharts(el, getRevenueOptions(data));
                revenueChart.render();
            }
        }

        // Initialize
        const initData = @js($chartData);
        initRevenueChart(initData);

        // Listen for updates
        $wire.on('revenue-chart-updated', ({ chartData }) => {
            if (chartData) {
                initRevenueChart(chartData);
            }
        });

        // Dark mode observer
        const observer = new MutationObserver(() => {
            const currentData = @js($chartData);
            initRevenueChart(currentData);
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    </script>
    @endscript
</div>
