<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$b = \App\Models\Booking::latest()->first();
echo json_encode($b->accommodations->toArray(), JSON_PRETTY_PRINT);
