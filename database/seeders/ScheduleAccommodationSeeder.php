<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleAccommodation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleAccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For each schedule, add some sample accommodations
        Schedule::chunk(50, function ($schedules) {
            foreach ($schedules as $schedule) {
                $accommodations = [
                    ['name' => 'Economy', 'description' => 'Standard seating, no bed', 'price' => 0, 'has_bed' => false, 'sort_order' => 0],
                    ['name' => 'Business', 'description' => 'Extra legroom, reclining seat', 'price' => 300, 'has_bed' => false, 'sort_order' => 1],
                    ['name' => 'First Class', 'description' => 'Private cabin with bed', 'price' => 800, 'has_bed' => true, 'sort_order' => 2],
                ];

                foreach ($accommodations as $accommodation) {
                    ScheduleAccommodation::firstOrCreate(
                        ['schedule_id' => $schedule->id, 'name' => $accommodation['name']],
                        [
                            'description' => $accommodation['description'],
                            'price' => $accommodation['price'],
                            'has_bed' => $accommodation['has_bed'],
                            'is_active' => true,
                            'sort_order' => $accommodation['sort_order'],
                        ]
                    );
                }
            }
        });
    }
}
