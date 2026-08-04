<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredPayments extends Command
{
    protected $signature = 'payments:cancel-expired';
    protected $description = 'Cancel bookings whose 1-hour payment window has expired without proof of payment.';

    public function handle(): int
    {
        $now = Carbon::now();

        // Find transactions that:
        // - have a payment_deadline_at in the past
        // - still have no proof_of_payment uploaded
        // - payment_status is NOT already 'pending' (paid) or cancelled
        $expired = Transaction::query()
            ->whereNotNull('payment_deadline_at')
            ->where('payment_deadline_at', '<=', $now)
            ->whereNull('proof_of_payment')
            ->whereNotIn('payment_status', ['pending', 'paid', 'cancelled'])
            ->with('booking')
            ->get();

        $count = 0;

        foreach ($expired as $transaction) {
            $booking = $transaction->booking;

            if (! $booking) {
                continue;
            }

            // Skip if already cancelled
            if (in_array($booking->status, [Booking::STATUS_CANCELLED, Booking::STATUS_OPERATOR_CANCELLED])) {
                continue;
            }

            try {
                $booking->update(['status' => Booking::STATUS_CANCELLED]);
                $transaction->update(['payment_status' => 'cancelled']);

                Log::info('Booking auto-cancelled due to payment timeout.', [
                    'booking_id' => $booking->id,
                    'transaction_number' => $booking->transaction_number,
                    'deadline' => $transaction->payment_deadline_at,
                ]);

                $count++;
            } catch (\Throwable $e) {
                Log::error('Failed to auto-cancel booking for expired payment.', [
                    'booking_id' => $booking->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Cancelled {$count} expired booking(s).");

        return Command::SUCCESS;
    }
}
