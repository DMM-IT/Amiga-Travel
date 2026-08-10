<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyExpiringVouchers extends Command
{
    protected $signature = 'vouchers:notify-expiring';
    protected $description = 'Send notifications for vouchers expiring in exactly 3 days.';

    public function handle(): int
    {
        $targetDate = Carbon::now()->addDays(3)->toDateString();

        $vouchers = Voucher::where('is_active', true)
            ->whereNotNull('end_at')
            ->whereDate('end_at', $targetDate)
            ->where('is_hidden', false)
            ->get();

        if ($vouchers->isEmpty()) {
            $this->info("No vouchers expiring on {$targetDate}.");
            return Command::SUCCESS;
        }

        $users = User::all();
        $count = 0;

        foreach ($vouchers as $voucher) {
            foreach ($users as $user) {
                UserNotification::notify(
                    $user->id,
                    "Voucher Expiring Soon!",
                    "Don't forget! The voucher '{$voucher->name}' (Code: {$voucher->code}) expires in 3 days.",
                    'voucher',
                    'card_giftcard'
                );
            }
            $count++;
        }

        $this->info("Sent notifications for {$count} expiring voucher(s) to {$users->count()} users.");
        return Command::SUCCESS;
    }
}
