<?php

namespace Database\Seeders;

use App\Models\VehicleRate;
use Illuminate\Database\Seeder;

class VehicleRateSeeder extends Seeder
{
    /**
     * Seed vehicle types from bicycle to shipping truck.
     * Prices are placeholders — update them in the admin panel.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Bicycle', 'price' => 150, 'sort_order' => 1],
            ['name' => 'Motorcycle', 'price' => 350, 'sort_order' => 2],
            ['name' => 'Tricycle', 'price' => 450, 'sort_order' => 3],
            ['name' => 'Sedan / Car', 'price' => 1200, 'sort_order' => 4],
            ['name' => 'SUV', 'price' => 1500, 'sort_order' => 5],
            ['name' => 'Van', 'price' => 1800, 'sort_order' => 6],
            ['name' => 'Pickup Truck', 'price' => 2200, 'sort_order' => 7],
            ['name' => 'Light Truck', 'price' => 3500, 'sort_order' => 8],
            ['name' => 'Medium Truck', 'price' => 5500, 'sort_order' => 9],
            ['name' => 'Shipping Truck', 'price' => 8500, 'sort_order' => 10],
        ];

        foreach ($types as $type) {
            VehicleRate::updateOrCreate(
                ['name' => $type['name']],
                [
                    'price' => $type['price'],
                    'sort_order' => $type['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }
}
