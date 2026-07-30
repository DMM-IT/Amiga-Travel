<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = \App\Models\Booking::with(['schedule.ferryRoute', 'returnSchedule.ferryRoute', 'transportClasses'])->latest()->first();

if (!$booking) {
    echo "No booking found.\n";
    exit;
}

echo "Booking ID: " . $booking->id . "\n";
if ($booking->schedule) {
    echo "Ferry Route ID: " . $booking->schedule->ferry_route_id . "\n";
    echo "Original Time: " . \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i:s') . "\n";
    echo "Accommodation: " . $booking->schedule_accommodation_name . "\n";
    
    // Find matching schedules
    $schedules = \App\Models\Schedule::where('ferry_route_id', $booking->schedule->ferry_route_id)
        ->whereTime('departure_time', \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i:s'))
        ->where('departure_time', '>', now())
        ->get();
        
    echo "Found " . $schedules->count() . " matching future schedules.\n";
}
