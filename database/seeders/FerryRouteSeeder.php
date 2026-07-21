<?php

namespace Database\Seeders;

use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FerryRouteSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old routes and schedules to prevent duplicates with old logic
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schedule::truncate();
        FerryRoute::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ferries = Vehicle::where('type', 'ferry')->where('is_active', true)->get();
        $airlines = Vehicle::where('type', 'airline')->where('is_active', true)->get();

        $ferryRoutes = [
            ['Manila', 'Boracay'],
            ['Manila', 'Bohol'],
            ['Manila', 'Cebu'],
            ['Batangas', 'Boracay'],
            ['Cebu', 'Bohol'],
        ];

        $ferryTemplates = [
            [
                'duration_minutes' => 210,
                'price' => 1800,
                'availability_label' => 'Available',
                'departure_time' => '08:00',
                'arrival_time' => '11:30',
            ],
            [
                'duration_minutes' => 210,
                'price' => 2200,
                'availability_label' => 'Limited availability',
                'departure_time' => '10:00',
                'arrival_time' => '13:30',
            ],
            [
                'duration_minutes' => 210,
                'price' => 2700,
                'availability_label' => 'Available',
                'departure_time' => '12:00',
                'arrival_time' => '15:30',
            ],
        ];

        foreach ($ferries as $index => $ferry) {
            $routePair = $ferryRoutes[$index % count($ferryRoutes)];
            
            $route = FerryRoute::updateOrCreate(
                [
                    'origin' => $routePair[0],
                    'destination' => $routePair[1],
                    'mode' => 'ferry',
                    'operator' => $ferry->operator,
                ],
                [
                    'vehicle_id' => $ferry->id,
                    'is_active' => true,
                ]
            );

            foreach ($ferryTemplates as $template) {
                for ($i = 0; $i < 7; $i++) {
                    $date = Carbon::today()->addDays($i)->format('Y-m-d');
                    $depTime = Carbon::parse($date . ' ' . $template['departure_time']);
                    $arrTime = Carbon::parse($date . ' ' . $template['arrival_time']);
                    
                    if ($arrTime->lessThan($depTime)) {
                        $arrTime->addDay();
                    }
                    
                    Schedule::create(array_merge($template, [
                        'ferry_route_id' => $route->id,
                        'service_name' => $ferry->name,
                        'vehicle_name' => $ferry->vehicle_id,
                        'departure_time' => $depTime,
                        'arrival_time' => $arrTime,
                        'is_active' => true,
                    ]));
                }
            }
        }

        $airlineRoutes = [
            ['Manila', 'Cebu'],
            ['Manila', 'Davao'],
            ['Manila', 'Iloilo'],
            ['Cebu', 'Davao'],
            ['Manila', 'Palawan'],
        ];

        $airlineTemplates = [
            [
                'duration_minutes' => 90,
                'price' => 1500,
                'availability_label' => 'Available',
                'departure_time' => '06:00',
                'arrival_time' => '07:30',
            ],
            [
                'duration_minutes' => 90,
                'price' => 2000,
                'availability_label' => 'Limited availability',
                'departure_time' => '12:00',
                'arrival_time' => '13:30',
            ],
        ];

        foreach ($airlines as $index => $airline) {
            $routePair = $airlineRoutes[$index % count($airlineRoutes)];
            
            $route = FerryRoute::updateOrCreate(
                [
                    'origin' => $routePair[0],
                    'destination' => $routePair[1],
                    'mode' => 'airline',
                    'operator' => $airline->operator,
                ],
                [
                    'vehicle_id' => $airline->id,
                    'is_active' => true,
                ]
            );

            foreach ($airlineTemplates as $template) {
                for ($i = 0; $i < 7; $i++) {
                    $date = Carbon::today()->addDays($i)->format('Y-m-d');
                    $depTime = Carbon::parse($date . ' ' . $template['departure_time']);
                    $arrTime = Carbon::parse($date . ' ' . $template['arrival_time']);
                    
                    if ($arrTime->lessThan($depTime)) {
                        $arrTime->addDay();
                    }
                    
                    Schedule::create(array_merge($template, [
                        'ferry_route_id' => $route->id,
                        'service_name' => $airline->name,
                        'vehicle_name' => $airline->vehicle_id,
                        'departure_time' => $depTime,
                        'arrival_time' => $arrTime,
                        'seat_rows' => null,
                        'seat_columns' => null,
                        'is_active' => true,
                    ]));
                }
            }
        }
    }
}
