<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Transaction;
use Filament\Widgets\Widget;

class DashboardSummaryWidget extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-summary-widget';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 2;

    public function getViewData(): array
    {
        return [
            'recentBookings' => Booking::latest('created_at')
                ->take(5)
                ->get(['transaction_number', 'client_name', 'origin', 'destination', 'departure_date', 'return_date', 'status', 'total_price', 'created_at']),
            'recentTransactions' => Transaction::with('booking')
                ->latest('created_at')
                ->take(5)
                ->get(),
        ];
    }
}
