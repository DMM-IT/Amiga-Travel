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
                    for ($i = 0; $i < 7; $i++) {
                        $date = \Carbon\Carbon::today()->addDays($i)->format('Y-m-d');
                        $depTime = \Carbon\Carbon::parse($date . ' ' . $template['departure_time']);
                        $arrTime = \Carbon\Carbon::parse($date . ' ' . $template['arrival_time']);
                        if ($arrTime->lessThan($depTime)) {
                            $arrTime->addDay();
                        }
                        Schedule::updateOrCreate(
                            [
                                'ferry_route_id' => $route->id,
                                'service_name' => $template['service_name'],
                                'departure_time' => $depTime,
                            ],
                            array_merge($template, [
                                'arrival_time' => $arrTime,
                                'is_active' => true,
                            ])
                        );
                    }
                }
            }
        }

        // Airline operators and routes
        $airlineOperators = ['Cebu Pacific', 'Philippine AirAsia', 'Philippine Airlines'];
        $airlineSeatingConfig = config('airline_seating.operators');

        $airlineRoutes = [
            ['Manila', 'Cebu'],
            ['Manila', 'Davao'],
            ['Manila', 'Iloilo'],
        ];

        $airlineTemplates = [
            [
                'service_name' => 'Morning Flight',
                'departure_time' => '06:00',
                'arrival_time' => '07:30',
                'duration_minutes' => 90,
                'price' => 1500,
                'availability_label' => 'Available',
            ],
            [
                'service_name' => 'Midday Flight',
                'departure_time' => '12:00',
                'arrival_time' => '13:30',
                'duration_minutes' => 90,
                'price' => 2000,
                'availability_label' => 'Limited availability',
            ],
        ];

        foreach ($airlineRoutes as $routeIndex => [$origin, $destination]) {
            foreach ($airlineOperators as $operator) {
                $route = FerryRoute::updateOrCreate(
                    ['origin' => $origin, 'destination' => $destination, 'mode' => 'airline', 'operator' => $operator],
                    ['is_active' => true, 'mode' => 'airline', 'operator' => $operator],
                );

                $operatorAircraft = array_keys($airlineSeatingConfig[$operator]['aircraft'] ?? []);

                foreach ($airlineTemplates as $templateIndex => $template) {
                    $aircraftType = $operatorAircraft[($routeIndex * count($airlineTemplates) + $templateIndex) % count($operatorAircraft)] ?? null;

                    for ($i = 0; $i < 7; $i++) {
                        $date = \Carbon\Carbon::today()->addDays($i)->format('Y-m-d');
                        $depTime = \Carbon\Carbon::parse($date . ' ' . $template['departure_time']);
                        $arrTime = \Carbon\Carbon::parse($date . ' ' . $template['arrival_time']);
                        if ($arrTime->lessThan($depTime)) {
                            $arrTime->addDay();
                        }
                        Schedule::updateOrCreate(
                            [
                                'ferry_route_id' => $route->id,
                                'service_name' => $operator . ' ' . $template['service_name'],
                                'departure_time' => $depTime,
                            ],
                            array_merge(
                                $template,
                                [
                                    'service_name' => $operator . ' ' . $template['service_name'],
                                    'vehicle_name' => $aircraftType,
                                    'arrival_time' => $arrTime,
                                    'seat_rows' => null,
                                    'seat_columns' => null,
                                    'is_active' => true,
                                ]
                            ),
                        );
                    }
                }
            }
        }
    }
}
