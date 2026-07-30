<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FerryRoute;
use App\Models\Vehicle;

echo "--- Route Operators ---\n";
print_r(FerryRoute::pluck('operator', 'id')->unique()->all());

echo "\n--- Vehicle Operators ---\n";
print_r(Vehicle::pluck('operator', 'id')->unique()->all());

echo "\n--- FerryRoute::scheduleOperatorsFor('airline') ---\n";
print_r(FerryRoute::scheduleOperatorsFor('airline'));
