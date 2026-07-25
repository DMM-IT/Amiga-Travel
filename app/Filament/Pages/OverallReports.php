<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Support\ReportingService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class OverallReports extends Page implements HasForms
{
    use InteractsWithForms;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('overall_reports');
    }

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Overall Reports';

    protected static ?string $title = 'Overall Reports';

    protected static string $view = 'filament.pages.overall-reports';

    public string $period = 'month';

    public ?string $customStartDate = null;

    public ?string $customEndDate = null;

    public array $stats = [];

    public array $breakdown = [];

    public array $chartData = [];

    public array $recentBookings = [];

    public array $recentTransactions = [];

    public array $transportModeData = [];

    public array $topRoutesData = [];

    public array $passengerData = [];

    public array $paymentAnalytics = [];

    public $staffLeaderboard;

    public $tourPerformance;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                DatePicker::make('customStartDate')
                    ->label('Start Date')
                    ->native(false)
                    ->maxDate(now())
                    ->live()
                    ->extraAttributes([
                        'class' => 'rounded-3xl border border-slate-300 bg-white text-slate-900 shadow-sm ring-1 ring-gray-200 transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:ring-white/10',
                    ])
                    ->extraTriggerAttributes([
                        'class' => 'w-full rounded-3xl bg-transparent px-4 py-3 text-left text-sm leading-6 text-slate-900 placeholder:text-slate-400 min-h-[52px] focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 dark:text-slate-100 dark:placeholder:text-slate-500',
                    ])
                    ->suffixIcon('heroicon-m-calendar')
                    ->afterStateUpdated(fn () => $this->applyCustomDates()),
                DatePicker::make('customEndDate')
                    ->label('End Date')
                    ->native(false)
                    ->maxDate(now())
                    ->live()
                    ->extraAttributes([
                        'class' => 'rounded-3xl border border-slate-300 bg-white text-slate-900 shadow-sm ring-1 ring-gray-200 transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:ring-white/10',
                    ])
                    ->extraTriggerAttributes([
                        'class' => 'w-full rounded-3xl bg-transparent px-4 py-3 text-left text-sm leading-6 text-slate-900 placeholder:text-slate-400 min-h-[52px] focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 dark:text-slate-100 dark:placeholder:text-slate-500',
                    ])
                    ->suffixIcon('heroicon-m-calendar')
                    ->afterStateUpdated(fn () => $this->applyCustomDates()),
            ]);
    }

    public function applyCustomDates(): void
    {
        if ($this->customStartDate && $this->customEndDate) {
            $this->period = 'custom';
            $this->loadStats();
        }
    }

    public function loadStats(): void
    {
        $service = app(ReportingService::class);
        $start = $this->period === 'custom' ? $this->customStartDate : null;
        $end = $this->period === 'custom' ? $this->customEndDate : null;
        $period = $this->period === 'custom' ? null : $this->period;

        $this->stats = $service->getOverallStats($period, $start, $end);
        $this->breakdown = $service->getBookingStatusBreakdown($period, $start, $end);

        $periodCharts = $service->getRevenueByPeriod($period, $start, $end);
        $statusDist = $service->getBookingStatusDistribution($period, $start, $end);
        $transportMode = $service->getBookingsByTransportMode($period, $start, $end);
        $topRoutes = $service->getTopRoutesByRevenue(8, $period, $start, $end);
        $passengers = $service->getPassengerDemographics($period, $start, $end);

        $this->chartData = [
            'revenue' => $periodCharts['revenue'],
            'bookingVolume' => $periodCharts['bookingVolume'],
            'statusDistribution' => $statusDist,
            'transportMode' => $transportMode,
            'topRoutes' => $topRoutes,
            'passengers' => $passengers,
        ];

        $this->transportModeData = $transportMode;
        $this->topRoutesData = $topRoutes;
        $this->passengerData = $passengers;
        $this->paymentAnalytics = $service->getPaymentAnalytics($period, $start, $end);
        $this->staffLeaderboard = $service->getStaffLeaderboard($period, $start, $end);
        $this->tourPerformance = $service->getTourPerformance($period, $start, $end);
        $this->recentBookings = $service->getRecentBookings(8, $period, $start, $end);
        $this->recentTransactions = $service->getRecentTransactions(8, $period, $start, $end);

        $this->dispatch('report-charts-updated', chartData: $this->chartData);
    }

    public function refreshData(): void
    {
        $this->loadStats();
    }

    public function updatedPeriod(): void
    {
        if ($this->period !== 'custom') {
            $this->customStartDate = null;
            $this->customEndDate = null;
        }
        $this->loadStats();
    }
}
