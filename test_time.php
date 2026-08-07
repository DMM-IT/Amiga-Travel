<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$s = App\Models\Schedule::first();
echo "Raw: " . $s->departure_time . "\n";
echo "Array: " . $s->toArray()['departure_time'] . "\n";
echo "Parsed: " . \Carbon\Carbon::parse($s->toArray()['departure_time'])->toDateTimeString() . "\n";
echo "Now: " . now()->toDateTimeString() . "\n";
echo "isAfter: " . (\Carbon\Carbon::parse($s->toArray()['departure_time'])->isAfter(now()) ? 'true' : 'false') . "\n";
