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
        $daily = [1, 2, 3, 4, 5, 6, 7];

        // Ferry operators
        $ferryOperators = ['Starlight', '2GO'];

        $ferryRoutes = [
            ['Manila', 'Boracay'],
            ['Manila', 'Bohol'],
            ['Manila', 'Cebu'],
            ['Batangas', 'Boracay'],
            ['Cebu', 'Bohol'],
        ];

        $ferryTemplates = [
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

        foreach ($ferryRoutes as [$origin, $destination]) {
            foreach ($ferryOperators as $operator) {
                $route = FerryRoute::updateOrCreate(
                    ['origin' => $origin, 'destination' => $destination, 'mode' => 'ferry', 'operator' => $operator],
                    ['is_active' => true, 'mode' => 'ferry', 'operator' => $operator],
                );

                foreach ($ferryTemplates as $template) {
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

        // Airline operators and routes
        $airlineOperators = ['Cebu Pacific', 'Philippine AirAsia', 'Philippine Airlines'];

        $airlineRoutes = [
            ['Manila', 'Cebu'],
            ['Manila', 'Davao'],
            ['Manila', 'Iloilo'],
        ];

        $airlineTemplates = [
            [
                'service_name' => 'Economy',
                'departure_time' => '06:00',
                'arrival_time' => '07:30',
                'duration_minutes' => 90,
                'price' => 1500,
                'availability_label' => 'Available',
            ],
            [
                'service_name' => 'Midday',
                'departure_time' => '12:00',
                'arrival_time' => '13:30',
                'duration_minutes' => 90,
                'price' => 2000,
                'availability_label' => 'Limited availability',
            ],
        ];

        foreach ($airlineRoutes as [$origin, $destination]) {
            foreach ($airlineOperators as $operator) {
                $route = FerryRoute::updateOrCreate(
                    ['origin' => $origin, 'destination' => $destination, 'mode' => 'airline', 'operator' => $operator],
                    ['is_active' => true, 'mode' => 'airline', 'operator' => $operator],
                );

                foreach ($airlineTemplates as $template) {
                    Schedule::updateOrCreate(
                        [
                            'ferry_route_id' => $route->id,
                            'service_name' => $operator . ' ' . $template['service_name'],
                            'departure_time' => $template['departure_time'],
                        ],
                        [
                            ...$template,
                            'service_name' => $operator . ' ' . $template['service_name'],
                            'operating_days' => $daily,
                            'is_active' => true,
                        ],
                    );
                }
            }
        }
    }
}
