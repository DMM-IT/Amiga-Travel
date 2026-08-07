<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RetrofitReferrals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrofit:referrals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for existing users who do not have one';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting referral code retrofit...');

        $users = User::whereNull('referral_code')->orWhere('referral_code', '')->get();
        $count = 0;

        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        foreach ($users as $user) {
            do {
                $code = '';
                for ($i = 0; $i < 7; $i++) {
                    $code .= $chars[random_int(0, strlen($chars) - 1)];
                }
            } while (User::where('referral_code', $code)->exists());

            $user->referral_code = $code;
            $user->save();
            $count++;
        }

        $this->info("Successfully assigned referral codes to {$count} legacy users.");
        return 0;
    }
}
