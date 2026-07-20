<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Inquiry;
use Illuminate\Support\Collection;

class AdminNotificationFeed
{
    public function getForUser(): Collection
    {
        $notifications = collect();

        $bookings = Booking::query()
            ->latest('created_at')
            ->limit(20)
            ->get();

        foreach ($bookings as $booking) {
            if ($booking->status === 'cancelled') {
                $notifications->push([
                    'id' => 'booking-cancel-' . $booking->id,
                    'type' => 'cancellation',
                    'title' => 'Booking cancelled',
                    'message' => $booking->client_name . ' cancelled booking #' . $booking->transaction_number,
                    'created_at' => $booking->updated_at ?? $booking->created_at,
                    'url' => '/admin/bookings/' . $booking->id,
                ]);
            }

            if ($booking->status === 'pending' && ! $booking->is_rebooked) {
                $notifications->push([
                    'id' => 'booking-new-' . $booking->id,
                    'type' => 'new_booking',
                    'title' => 'New booking',
                    'message' => $booking->client_name . ' placed booking #' . $booking->transaction_number,
                    'created_at' => $booking->created_at,
                    'url' => '/admin/bookings/' . $booking->id,
                ]);
            }

            if ($booking->is_rebooked && $booking->rebooking_status === 'pending') {
                $notifications->push([
                    'id' => 'booking-rebook-' . $booking->id,
                    'type' => 'rebooking',
                    'title' => 'Rebooking request',
                    'message' => $booking->client_name . ' submitted a rebooking request for #' . $booking->transaction_number,
                    'created_at' => $booking->updated_at ?? $booking->created_at,
                    'url' => '/admin/bookings/' . $booking->id,
                ]);
            }
        }

        $inquiries = Inquiry::query()
            ->latest('created_at')
            ->limit(20)
            ->get();

        foreach ($inquiries as $inquiry) {
            $notifications->push([
                'id' => 'inquiry-' . $inquiry->id,
                'type' => 'inquiry',
                'title' => 'New inquiry',
                'message' => $inquiry->name . ' sent an inquiry: ' . $inquiry->subject,
                'created_at' => $inquiry->created_at,
                'url' => '/admin',
            ]);
        }

        return $notifications
            ->sortByDesc('created_at')
            ->values();
    }
}
