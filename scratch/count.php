<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$route = App\Models\FerryRoute::where('origin', 'Batangas')->where('destination', 'Boracay')->first();
$schedules = $route->schedules()->select('departure_time')->get();
$grouped = $schedules->groupBy(function($s) {
    return \Carbon\Carbon::parse($s->departure_time)->format('Y-m-d');
})->map->count()->toArray();
print_r($grouped);
