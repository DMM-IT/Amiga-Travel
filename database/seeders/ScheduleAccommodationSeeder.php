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
        // For each ferry schedule, add appropriate accommodations
        Schedule::whereHas('ferryRoute', function ($query) {
            $query->where('mode', 'ferry');
        })->with('ferryRoute')->chunk(50, function ($schedules) {
            foreach ($schedules as $schedule) {
                $operator = $schedule->ferryRoute->operator ?? '';

                // Different accommodation options based on the operator
                if (strtolower($operator) === 'starlight') {
                    $accommodations = [
                        [
                            'name' => 'Reclining Seats',
                            'description' => 'Air-conditioned cabin, comfortable reclining seats',
                            'price' => 0.00,
                            'has_bed' => false,
                            'sort_order' => 0,
                        ],
                        [
                            'name' => 'Tourist Class',
                            'description' => 'Shared air-conditioned cabin with bunk beds',
                            'price' => 250.00,
                            'has_bed' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'name' => 'Cabin for 4/8',
                            'description' => 'Cozy shared or private cabin with comfortable bunks',
                            'price' => 600.00,
                            'has_bed' => true,
                            'sort_order' => 2,
                        ],
                        [
                            'name' => 'Suite Room',
                            'description' => 'Private room with matrimonial bed and private toilet/bath',
                            'price' => 1200.00,
                            'has_bed' => true,
                            'sort_order' => 3,
                        ],
                    ];
                } elseif (strtolower($operator) === '2go') {
                    $accommodations = [
                        [
                            'name' => 'Super Value Class',
                            'description' => 'Non-airconditioned shared bunk beds',
                            'price' => 0.00,
                            'has_bed' => true,
                            'sort_order' => 0,
                        ],
                        [
                            'name' => 'Tourist Class',
                            'description' => 'Shared air-conditioned cabin with bunk beds',
                            'price' => 300.00,
                            'has_bed' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'name' => 'Cabin for 4/6',
                            'description' => 'Cozy private or shared cabin with bunks',
                            'price' => 750.00,
                            'has_bed' => true,
                            'sort_order' => 2,
                        ],
                        [
                            'name' => 'State Room',
                            'description' => 'Private premium cabin for 2-4 passengers',
                            'price' => 1500.00,
                            'has_bed' => true,
                            'sort_order' => 3,
                        ],
                        [
                            'name' => 'Suite Room',
                            'description' => 'Luxury private room with television, private bathroom, and amenities',
                            'price' => 2500.00,
                            'has_bed' => true,
                            'sort_order' => 4,
                        ],
                    ];
                } else {
                    // Fallback accommodations for other ferry operators
                    $accommodations = [
                        [
                            'name' => 'Economy Class',
                            'description' => 'Standard non-aircon seating/bunks',
                            'price' => 0.00,
                            'has_bed' => false,
                            'sort_order' => 0,
                        ],
                        [
                            'name' => 'Tourist Class',
                            'description' => 'Air-conditioned room with bunk beds',
                            'price' => 200.00,
                            'has_bed' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'name' => 'Cabin Class',
                            'description' => 'Comfortable private/shared cabin with bunks',
                            'price' => 500.00,
                            'has_bed' => true,
                            'sort_order' => 2,
                        ],
                    ];
                }

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
