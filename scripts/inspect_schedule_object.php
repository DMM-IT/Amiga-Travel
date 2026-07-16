<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Schedule;
use App\Models\FerryRoute;
use App\Models\TransportClass;

$schedule = new Schedule();
$schedule->vehicle_name = null;
$schedule->setRelation('ferryRoute', new FerryRoute(['operator' => 'Philippine Airlines']));
$schedule->setRelation('transportClasses', collect([
    new TransportClass(['code' => 'economy', 'name' => 'Economy Class']),
    new TransportClass(['code' => 'premium-economy', 'name' => 'Premium Economy / Comfort Class']),
    new TransportClass(['code' => 'business', 'name' => 'Business Class']),
]));

$reflection = new ReflectionMethod($schedule, 'getTransportClassCodes');
$reflection->setAccessible(true);
$codes = $reflection->invoke($schedule);

var_dump($codes);
$profile = $schedule->getAirlineSeatingProfile();
var_export($profile);
echo "\n";
