<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Support\ReportingService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class OverallReports extends Page
{
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user instanceof User && ($user->is_admin || $user->hasAdminPermission('manage_bookings'));
    }

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Overall Reports';
    protected static ?string $title = 'Overall Reports';
    protected static string $view = 'filament.pages.overall-reports';

    public string $period = 'month';
    public array $stats = [];
    public array $breakdown = [];
    public array $revenueData = [];
    public array $recentBookings = [];
    public array $recentTransactions = [];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $service = app(ReportingService::class);
        $this->stats = $service->getOverallStats($this->period);
        $this->breakdown = $service->getBookingStatusBreakdown($this->period);
        $this->revenueData = $service->getRevenueByPeriod(30);

        $this->recentBookings = Booking::latest('created_at')
            ->take(5)
            ->get(['transaction_number', 'client_name', 'origin', 'destination', 'departure_date', 'return_date', 'status', 'total_price', 'created_at'])
            ->map(fn (Booking $booking) => [
                'transaction_number' => $booking->transaction_number,
                'client_name' => $booking->client_name,
                'route' => $booking->origin . ' → ' . $booking->destination,
                'travel_dates' => $booking->departure_date?->format('Y-m-d') . ($booking->return_date ? ' → ' . $booking->return_date->format('Y-m-d') : ''),
                'status' => ucfirst($booking->status),
                'total_price' => '₱' . number_format($booking->total_price, 2),
                'created_at' => $booking->created_at->format('Y-m-d'),
            ])
            ->toArray();

        $this->recentTransactions = Transaction::with('booking')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn (Transaction $transaction) => [
                'transaction_number' => $transaction->booking?->transaction_number ?? 'N/A',
                'payment_status' => ucfirst($transaction->payment_status),
                'rebooking_fee' => $transaction->rebooking_fee ? '₱' . number_format($transaction->rebooking_fee, 2) : '-',
                'proof_uploaded' => filled($transaction->rebooking_proof_of_payment) ? 'Yes' : 'No',
                'created_at' => $transaction->created_at->format('Y-m-d'),
            ])
            ->toArray();
    }

    public function updatedPeriod(): void
    {
        $this->loadStats();
    }
}
