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
        $classIdsByCode = [];

        foreach ($operatorConfigs as $operator => $operatorConfig) {
            foreach ($operatorConfig['classes'] ?? [] as $code => $classConfig) {
                $class = TransportClass::updateOrCreate(
                    ['operator' => $operator, 'code' => $code],
                    [
                        'name' => $classConfig['name'],
                        'description' => $classConfig['description'],
                        'price' => $classConfig['price'],
                        'sort_order' => $classConfig['sort_order'],
                        'is_active' => true,
                    ],
                );
                $classIdsByCode[$operator][$code] = $class->id;
            }
        }

        $airlineSchedules = Schedule::query()
            ->with('ferryRoute')
            ->whereHas('ferryRoute', fn ($q) => $q->where('mode', 'airline'))
            ->get();

        foreach ($airlineSchedules as $schedule) {
            $resolvedOperator = $schedule->resolveOperatorConfigKey($schedule->ferryRoute->operator);
            $operatorConfig = $operatorConfigs[$resolvedOperator] ?? null;
            if (! $operatorConfig) {
                continue;
            }

            $resolvedType = $schedule->resolveAircraftConfigKey($schedule->service_name);
            $aircraftConfig = $operatorConfig['aircraft'][$resolvedType] ?? null;
            if (! $aircraftConfig) {
                continue;
            }

            $attachedClassIds = collect($aircraftConfig['class_order'] ?? [])
                ->map(fn (string $code) => $classIdsByCode[$resolvedOperator][$code] ?? null)
                ->filter()
                ->values()
                ->all();

            $schedule->transportClasses()->sync($attachedClassIds);
        }
    }
}