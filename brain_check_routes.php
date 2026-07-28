<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\FerryRoute::with('vehicle', 'schedules')->get() as $r) {
    echo "ID: {$r->id} | Mode: {$r->mode} | RouteOp: {$r->operator} | VehOp: " . optional($r->vehicle)->operator . " | Active: {$r->is_active} | Schedules: " . $r->schedules()->count() . " | ActiveSch: " . $r->schedules()->where('is_active', true)->count() . "\n";
}
