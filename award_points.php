<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = App\Models\Booking::find(2);
if ($booking) {
    app(App\Services\GraciaPointsService::class)->awardPointsForBooking($booking);
    echo "Points awarded for booking 2.\n";
} else {
    echo "Booking 2 not found.\n";
}
