<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportingService
{
    public function getOverallStats(?string $period = null): array
    {
        $query = Booking::query();
        
        if ($period && $period !== 'all') {
            if ($period === 'today') {
                $query->whereDate('created_at', '=', today(), 'and');
            } elseif ($period === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()], 'and', false);
            } elseif ($period === 'month') {
                $query->whereMonth('created_at', '=', now()->month, 'and')
                    ->whereYear('created_at', '=', now()->year, 'and');
            } elseif ($period === 'year') {
                $query->whereYear('created_at', '=', now()->year, 'and');
            }
        }

        $bookings = $query->get();
        
        // Calculate revenue from bookings with paid transactions
        $paidBookings = $bookings->filter(function (Booking $booking) {
            return $booking->transactions()->where('payment_status', 'paid')->exists();
        });
        
        $pendingBookings = $bookings->filter(function (Booking $booking) {
            return $booking->transactions()->where('payment_status', 'pending')->exists();
        });

        return [
            'total_bookings' => $bookings->count(),
            'completed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'total_revenue' => (float) $paidBookings->sum('total_price'),
            'pending_revenue' => (float) $pendingBookings->sum('total_price'),
            'cancelled_revenue' => (float) $bookings->where('status', 'cancelled')->sum('cancellation_fee'),
            'rebooking_count' => $bookings->where('is_rebooked', true)->count(),
        ];
    }

    public function getStaffStats(?string $date = null): Collection
    {
        $staffUsers = User::where('is_staff', '=', true, 'and')
            ->orWhere('is_admin', '=', true, 'and')
            ->get();

        return $staffUsers->map(function (User $user) use ($date) {
            // Get bookings verified by this staff member
            $query = Booking::where('verified_by_user_id', $user->id);
            
            if ($date) {
                $query->whereDate('verified_at', $date);
            }
            
            $bookings = $query->get();

            // Calculate revenue from bookings verified by this user with paid transactions
            $paidBookings = $bookings->filter(function (Booking $booking) {
                return $booking->transactions()->where('payment_status', 'paid')->exists();
            });

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'total_bookings_handled' => $bookings->count(),
                'completed_bookings' => $bookings->where('status', 'confirmed')->count(),
                'pending_bookings' => $bookings->where('status', 'pending')->count(),
                'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
                'total_revenue_handled' => (float) $paidBookings->sum('total_price'),
                'created_at' => $user->created_at,
            ];
        });
    }

    public function getBookingStatusBreakdown(?string $period = null): array
    {
        $query = Booking::query();
        
        if ($period && $period !== 'all') {
            if ($period === 'today') {
                $query->whereDate('created_at', '=', today(), 'and');
            } elseif ($period === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()], 'and', false);
            } elseif ($period === 'month') {
                $query->whereMonth('created_at', '=', now()->month, 'and')
                    ->whereYear('created_at', '=', now()->year, 'and');
            } elseif ($period === 'year') {
                $query->whereYear('created_at', '=', now()->year, 'and');
            }
        }

        $bookings = $query->get();

        return [
            'pending' => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];
    }

    public function getRevenueByPeriod(int $days = 30): array
    {
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            // Get bookings that were created on this date and have paid transactions
            $revenue = (float) Booking::whereDate('created_at', '=', $date, 'and')
                ->whereHas('transactions', function ($query) {
                    $query->where('payment_status', '=', 'paid', 'and');
                })
                ->sum('total_price');
            
            $data[] = [
                'date' => $date,
                'revenue' => $revenue,
            ];
        }

        return $data;
    }
}
