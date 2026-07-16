<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Schedule;

$airlineSchedules = Schedule::with(['ferryRoute', 'transportClasses'])
    ->whereHas('ferryRoute', function ($query) {
        $query->where('mode', 'airline');
    })
    ->limit(10)
    ->get();

if ($airlineSchedules->isEmpty()) {
    echo "no airline schedules\n";
    exit(0);
}

foreach ($airlineSchedules as $schedule) {
    $profile = $schedule->getAirlineSeatingProfile();
    $codes = $schedule->relationLoaded('transportClasses')
        ? $schedule->transportClasses->pluck('code')->all()
        : $schedule->transportClasses()->pluck('code')->all();

    $reflection = new ReflectionMethod($schedule, 'getTransportClassCodes');
    $reflection->setAccessible(true);
    $inferredCodes = $reflection->invoke($schedule);

    $arr = $schedule->toBookingArray();

    echo "--- schedule {$schedule->id} ---\n";
    echo "vehicle_name: '{$schedule->vehicle_name}'\n";
    echo "operator: '{$schedule->ferryRoute->operator}'\n";
    echo "service_name: '{$schedule->service_name}'\n";
    echo "relationLoaded transportClasses: " . ($schedule->relationLoaded('transportClasses') ? 'yes' : 'no') . "\n";
    echo "codes: " . implode(',', $codes) . "\n";
    echo "inferredCodes: " . implode(',', $inferredCodes) . "\n";
    echo "profile: ";
    var_export($profile);
    echo "\n";
    echo "aircraft_capacity: " . ($arr['aircraft_capacity'] ?? 'null') . "\n";
    echo "transport_classes:\n";
    foreach ($arr['transport_classes'] as $class) {
        echo "- id: {$class['id']} name: {$class['name']} code: {$class['code']} seat_capacity: " . ($class['seat_capacity'] ?? 'null') . " rows: " . count($class['seat_rows']) . "\n";
    }
    echo "\n";
}
