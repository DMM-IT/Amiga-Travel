<?php

namespace Database\Seeders;

use App\Models\FerryRoute;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class FerryRouteSeeder extends Seeder
{
    /**
     * Seed reseller routes and schedules. Prices are placeholders — update
     * them in the admin panel (Schedules) to match operator quotes.
     */
    public function run(): void
    {
        $routes = [
            ['Manila', 'Boracay'],
            ['Manila', 'Bohol'],
            ['Manila', 'Palawan'],
            ['Manila', 'Cebu'],
            ['Batangas', 'Boracay'],
            ['Batangas', 'Bohol'],
            ['Lucena', 'Boracay'],
            ['Cebu', 'Boracay'],
            ['Cebu', 'Bohol'],
            ['Cebu', 'Palawan'],
        ];

        $daily = [1, 2, 3, 4, 5, 6, 7];

        $scheduleTemplates = [
            [
                'service_name' => 'Fast Ferry',
                'departure_time' => '08:00',
                'arrival_time' => '11:30',
                'duration_minutes' => 210,
                'price' => 1800,
                'availability_label' => 'Available',
            ],
            [
                'service_name' => 'Express Ferry',
                'departure_time' => '10:00',
                'arrival_time' => '13:30',
                'duration_minutes' => 210,
                'price' => 2200,
                'availability_label' => 'Limited availability',
            ],
            [
                'service_name' => 'Express Ferry',
                'departure_time' => '12:00',
                'arrival_time' => '15:30',
                'duration_minutes' => 210,
                'price' => 2700,
                'availability_label' => 'Available',
            ],
        ];

        foreach ($routes as [$origin, $destination]) {
            $route = FerryRoute::updateOrCreate(
                ['origin' => $origin, 'destination' => $destination],
                ['is_active' => true],
            );

            foreach ($scheduleTemplates as $template) {
                Schedule::updateOrCreate(
                    [
                        'ferry_route_id' => $route->id,
                        'service_name' => $template['service_name'],
                        'departure_time' => $template['departure_time'],
                    ],
                    [
                        ...$template,
                        'operating_days' => $daily,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
