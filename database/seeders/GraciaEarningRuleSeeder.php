<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GraciaEarningRule;

class GraciaEarningRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GraciaEarningRule::firstOrCreate(
            ['name' => 'Standard Earning Rule'],
            [
                'spend_threshold_centavos' => 100000, // 1000 PHP
                'points_awarded' => 5,
                'is_active' => true,
                'starts_at' => now(),
            ]
        );
    }
}
