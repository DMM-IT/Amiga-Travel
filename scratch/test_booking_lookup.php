<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = \App\Models\Booking::whereHas('schedule')->latest()->first();

if (!$booking) {
    echo "No booking found.\n";
    exit;
}

echo "Testing with booking: " . $booking->transaction_number . "\n";
echo "Original Departure Date: " . $booking->departure_date->format('Y-m-d') . "\n";
echo "Original Time: " . \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i:s') . "\n";

$component = app(\Livewire\LivewireManager::class)->new('booking-lookup');
// Since it's livewire we can just use the component instance loosely
$component->transaction_number = $booking->transaction_number;
$component->search();
$component->confirmRebookingRequest();

echo "Available Departure Dates: \n";
print_r($component->availableRebookingDates);

if ($component->rebooking_is_round_trip) {
    echo "Available Return Dates: \n";
    print_r($component->availableRebookingReturnDates);
}
