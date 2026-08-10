<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    protected $signature = 'payments:send-reminders';
    protected $description = 'Send 15-minute payment reminders for bookings that are about to expire.';

    public function handle(): int
    {
        $now = Carbon::now();
        // Target time is exactly 15 minutes from now. We search for transactions expiring between 14.5 and 15.5 minutes from now to catch them once.
        $targetStart = $now->copy()->addMinutes(14)->addSeconds(30);
        $targetEnd = $now->copy()->addMinutes(15)->addSeconds(30);

        $expiring = Transaction::query()
            ->whereNotNull('payment_deadline_at')
            ->whereBetween('payment_deadline_at', [$targetStart, $targetEnd])
            ->whereNull('proof_of_payment')
            ->where('payment_status', 'unpaid')
            ->with('booking')
            ->get();

        $count = 0;

        foreach ($expiring as $transaction) {
            $booking = $transaction->booking;

            if (! $booking || $booking->status === Booking::STATUS_CANCELLED || $booking->status === Booking::STATUS_OPERATOR_CANCELLED) {
                continue;
            }

            $user = User::where('email', $booking->client_email)->first();
            if ($user) {
                UserNotification::notify(
                    $user->id,
                    "Payment Reminder",
                    "You have less than 15 minutes left to upload your payment proof for booking {$booking->transaction_number}.",
                    'reminder',
                    'alarm'
                );
                $count++;
            }
        }

        $this->info("Sent {$count} payment reminder(s).");
        return Command::SUCCESS;
    }
}
