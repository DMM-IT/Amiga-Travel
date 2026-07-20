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
            ['name' => 'MOTORCYCLE (100cc to 200cc)', 'price' => 400, 'sort_order' => 1],
            ['name' => 'MOTORCYCLE (250cc & above)', 'price' => 500, 'sort_order' => 2],
            ['name' => 'TRICYCLE', 'price' => 450, 'sort_order' => 3],
            ['name' => 'MULTICAB', 'price' => 650, 'sort_order' => 4],
            ['name' => 'AUV', 'price' => 700, 'sort_order' => 5],
            ['name' => 'HATCHBACK', 'price' => 900, 'sort_order' => 6],
            ['name' => 'OWNER / LIGHT CARS', 'price' => 1100, 'sort_order' => 7],
            ['name' => 'OWNER JEEP', 'price' => 1200, 'sort_order' => 8],
            ['name' => 'PICK - UP', 'price' => 1400, 'sort_order' => 9],
            ['name' => 'SUV', 'price' => 1500, 'sort_order' => 10],
            ['name' => 'VAN', 'price' => 1600, 'sort_order' => 11],
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
