<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\TransportClass;
use Illuminate\Database\Seeder;

class TransportClassSeeder extends Seeder
{
    public function run(): void
    {
        $operatorConfigs = config('airline_seating.operators', []);

        foreach ($operatorConfigs as $operator => $operatorConfig) {
            $classIdsByCode = [];

            foreach ($operatorConfig['classes'] ?? [] as $code => $classConfig) {
                $class = TransportClass::updateOrCreate(
                    [
                        'operator' => $operator,
                        'code' => $code,
                    ],
                    [
                        'name' => $classConfig['name'],
                        'description' => $classConfig['description'],
                        'price' => $classConfig['price'],
                        'sort_order' => $classConfig['sort_order'],
                        'is_active' => true,
                    ],
                );

                $classIdsByCode[$code] = $class->id;
            }

            $airlineSchedules = Schedule::query()
                ->with('ferryRoute')
                ->whereHas('ferryRoute', function ($query) use ($operator) {
                    $query->where('mode', 'airline')
                        ->where('operator', $operator);
                })
                ->get();

            foreach ($airlineSchedules as $schedule) {
                $aircraftConfig = $operatorConfig['aircraft'][$schedule->vehicle_name] ?? null;

                if (! $aircraftConfig) {
                    continue;
                }

                $attachedClassIds = collect($aircraftConfig['class_order'] ?? [])
                    ->map(fn (string $code) => $classIdsByCode[$code] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                $schedule->transportClasses()->sync($attachedClassIds);
            }
        }
    }
}
