<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\ScheduleAccommodation;
use App\Models\Booking;

class MyPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'My Page & Reports';

    protected static ?string $title = 'My Page & Reports';

    protected static ?string $slug = 'my-page';

    protected static string $view = 'filament.pages.my-page';

    public array $stats = [];

    public array $recentBookings = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $user = auth()->user();
        $userId = $user?->id;

        // Staff performance connection: bookings/transactions handled or verified by this user
        $baseQuery = Booking::query()->where(function ($q) use ($userId) {
            $q->where('verified_by_user_id', $userId)
              ->orWhere('user_id', $userId);
        });

        $total = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', 'confirmed')->count();
        $pending = (clone $baseQuery)->where('status', 'pending')->count();
        $cancelled = (clone $baseQuery)->where('status', 'cancelled')->count();
        $revenue = (clone $baseQuery)->where('status', 'confirmed')->sum('total_price') ?: 0;

        $completionRate = $total > 0 ? round(($completed / $total) * 100, 1) : 100;

        $this->stats = [
            'total_transactions' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'revenue_handled' => $revenue,
            'completion_rate' => $completionRate,
        ];

        $this->recentBookings = (clone $baseQuery)
            ->with(['schedule.ferryRoute'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function (Booking $booking) {
                $route = $booking->schedule && $booking->schedule->ferryRoute
                    ? "{$booking->schedule->ferryRoute->origin} - {$booking->schedule->ferryRoute->destination}"
                    : ($booking->origin && $booking->destination ? "{$booking->origin} - {$booking->destination}" : 'Trip Reservation');

                $status = strtolower($booking->status ?: 'pending');

                return [
                    'id' => $booking->id,
                    'reference' => $booking->reference_number ?: "BK-{$booking->id}",
                    'client' => $booking->client_name ?: ($booking->client_email ?: 'Client #' . $booking->id),
                    'route' => $route,
                    'status' => $status,
                    'payment_status' => $status === 'confirmed' ? 'paid' : 'pending',
                    'total_amount' => (float) $booking->total_price,
                    'date' => $booking->created_at ? $booking->created_at->format('M d, Y h:i A') : 'N/A',
                ];
            })
            ->all();
    }

    public function downloadReport(string $type)
    {
        $filename = "{$type}_report_" . now()->format('Y-m-d_H-i-s') . ".csv";

        return response()->streamDownload(function () use ($type) {
            $handle = fopen('php://output', 'w');
            
            if ($type === 'my_transactions') {
                $user = auth()->user();
                $userId = $user?->id;
                fputcsv($handle, ['ID', 'Reference Number', 'Client Name', 'Client Email', 'Client Phone', 'Route', 'Total Price', 'Staff Status (Completed/Pending/Cancelled)', 'Created At']);
                $myBookings = Booking::where(function ($q) use ($userId) {
                    $q->where('verified_by_user_id', $userId)
                      ->orWhere('user_id', $userId);
                })->latest()->get();
                foreach ($myBookings as $row) {
                    $route = ($row->origin && $row->destination) ? "{$row->origin} - {$row->destination}" : 'Trip';
                    fputcsv($handle, [
                        $row->id,
                        $row->reference_number ?? "BK-{$row->id}",
                        $row->client_name,
                        $row->client_email,
                        $row->client_phone,
                        $route,
                        $row->total_price,
                        ucfirst($row->status ?: 'pending'),
                        $row->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            } elseif ($type === 'ferry_routes') {
                fputcsv($handle, ['ID', 'Origin', 'Destination', 'Operator', 'Mode', 'Is Active', 'Created At']);
                foreach (FerryRoute::all() as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->origin,
                        $row->destination,
                        $row->operator,
                        $row->mode,
                        $row->is_active ? 'Active' : 'Inactive',
                        $row->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            } elseif ($type === 'schedules') {
                fputcsv($handle, ['ID', 'Route', 'Departure Time', 'Arrival Time', 'Vehicle', 'Price', 'Availability']);
                foreach (Schedule::with('ferryRoute')->get() as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->ferryRoute ? "{$row->ferryRoute->origin} - {$row->ferryRoute->destination}" : 'N/A',
                        $row->formatted_departure,
                        $row->formatted_arrival,
                        $row->vehicle_name,
                        $row->price,
                        $row->availability_label ?? 'Available',
                    ]);
                }
            } elseif ($type === 'accommodations') {
                fputcsv($handle, ['ID', 'Schedule ID', 'Route', 'Accommodation Name', 'Price', 'With Bed', 'Tickets Available', 'Is Active']);
                foreach (ScheduleAccommodation::with('schedule.ferryRoute')->get() as $row) {
                    $route = $row->schedule && $row->schedule->ferryRoute 
                        ? "{$row->schedule->ferryRoute->origin} - {$row->schedule->ferryRoute->destination}" 
                        : 'N/A';
                    fputcsv($handle, [
                        $row->id,
                        $row->schedule_id,
                        $route,
                        $row->name,
                        $row->price,
                        $row->has_bed ? 'Yes' : 'No',
                        $row->tickets_available,
                        $row->is_active ? 'Active' : 'Inactive',
                    ]);
                }
            } elseif ($type === 'bookings') {
                fputcsv($handle, ['ID', 'Reference Number', 'Client Name', 'Client Email', 'Client Phone', 'Total Price', 'Payment Status', 'Booking Status', 'Created At']);
                foreach (Booking::latest()->take(500)->get() as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->reference_number ?? "BK-{$row->id}",
                        $row->client_name,
                        $row->client_email,
                        $row->client_phone,
                        $row->total_price,
                        $row->status === 'confirmed' ? 'Paid' : 'Pending',
                        $row->status,
                        $row->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                fputcsv($handle, ['Report Type Not Found']);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
