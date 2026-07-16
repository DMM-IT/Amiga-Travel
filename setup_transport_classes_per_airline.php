<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TransportClass;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

echo "=== Setting up transport classes per airline ===\n\n";

// First, delete any existing transport classes to start fresh
echo "Deleting existing transport classes...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');
DB::table('transport_classes')->truncate();
DB::table('schedule_transport_class')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Define transport classes per airline
$airlineClasses = [
    'Philippine Airlines' => [
        [
            'name' => 'Economy Class',
            'description' => 'Standard seating with personal entertainment screens or USB charging.',
            'price' => 0,
            'is_active' => true,
        ],
        [
            'name' => 'Premium Economy / Comfort Class',
            'description' => 'Wider seats, extra legroom, more recline, priority boarding, and extra baggage allowances.',
            'price' => 3000,
            'is_active' => true,
        ],
        [
            'name' => 'Business Class',
            'description' => 'Lie-flat beds, direct aisle access, personal TV screens, and premium dining.',
            'price' => 10000,
            'is_active' => true,
        ],
    ],
    'Philippine AirAsia' => [
        [
            'name' => 'Economy Class',
            'description' => 'Standard budget-friendly seats (16-17 inch width, ~28 inch legroom).',
            'price' => 0,
            'is_active' => true,
        ],
        [
            'name' => 'Hot Seats',
            'description' => 'Upgraded Economy seats at front/emergency exits with up to 20 inches extra legroom and priority boarding.',
            'price' => 1500,
            'is_active' => true,
        ],
        [
            'name' => 'Premium Flatbed',
            'description' => 'Business Class seats that recline into flat beds with free checked bags, duvet, pillow, and free meals (AirAsia X long-haul only).',
            'price' => 8000,
            'is_active' => true,
        ],
    ],
    'Cebu Pacific' => [
        [
            'name' => 'Standard',
            'description' => 'Regular economy seats of your choice.',
            'price' => 0,
            'is_active' => true,
        ],
        [
            'name' => 'Standard Plus',
            'description' => 'Extra legroom seats with no priority boarding benefit.',
            'price' => 800,
            'is_active' => true,
        ],
        [
            'name' => 'Premium',
            'description' => 'Extra legroom seats located near emergency exits and lavatories.',
            'price' => 1500,
            'is_active' => true,
        ],
    ],
];

// Create the transport classes and track their IDs
$createdClasses = [];
foreach ($airlineClasses as $airline => $classes) {
    foreach ($classes as $classData) {
        $class = TransportClass::create($classData);
        $createdClasses[$airline][] = $class->id;
        echo "Created: {$class->name} (ID: {$class->id})\n";
    }
}

echo "\n=== Attaching transport classes to schedules by operator ===\n";

// Get all airline schedules with their ferry route's operator
$schedules = Schedule::with('ferryRoute')
    ->whereHas('ferryRoute', function ($q) {
        $q->where('mode', 'airline');
    })
    ->get();

foreach ($schedules as $schedule) {
    $operator = $schedule->ferryRoute->operator;
    echo "Processing schedule: {$schedule->service_name} (ID: {$schedule->id}, Operator: {$operator})\n";

    if (isset($createdClasses[$operator])) {
        $schedule->transportClasses()->sync($createdClasses[$operator]);
        echo "  Attached classes: " . implode(', ', array_map(function ($id) {
            return TransportClass::find($id)->name;
        }, $createdClasses[$operator])) . "\n";
    } else {
        echo "  WARNING: No classes defined for operator {$operator}\n";
    }
}

echo "\n=== Setup complete! ===";
