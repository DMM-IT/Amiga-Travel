<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Illuminate\Console\Command;

class PurgeExpiredSchedules extends Command
{
    protected $signature = 'schedules:purge-expired';

    protected $description = 'Delete schedules whose arrival time is older than 24 hours';

    public function handle(): int
    {
        $cutoff = now()->subDay();

        $schedules = Schedule::query()
            ->whereNotNull('arrival_time')
            ->where('arrival_time', '<=', $cutoff)
            ->get();

        $count = 0;

        foreach ($schedules as $schedule) {
            $schedule->delete();
            $count++;
        }

        $this->info("Purged {$count} expired schedule(s).");

        return self::SUCCESS;
    }
}
