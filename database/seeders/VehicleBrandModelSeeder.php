<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleBrandModelSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Toyota',
                'sort_order' => 1,
                'models' => [
                    ['name' => 'Vios', 'price' => 1150],
                    ['name' => 'Innove', 'price' => 1350],
                    ['name' => 'Fortuner', 'price' => 1800],
                ],
            ],
            [
                'name' => 'Mitsubishi',
                'sort_order' => 2,
                'models' => [
                    ['name' => 'L300', 'price' => 1250],
                    ['name' => 'Montero Sport', 'price' => 1900],
                    ['name' => 'Strada', 'price' => 1500],
                ],
            ],
            [
                'name' => 'Hyundai',
                'sort_order' => 3,
                'models' => [
                    ['name' => 'Accent', 'price' => 1200],
                    ['name' => 'Starex', 'price' => 2100],
                    ['name' => 'Tucson', 'price' => 1700],
                ],
            ],
            [
                'name' => 'Honda',
                'sort_order' => 4,
                'models' => [
                    ['name' => 'City', 'price' => 1100],
                    ['name' => 'Civic', 'price' => 1250],
                    ['name' => 'CR-V', 'price' => 1650],
                ],
            ],
        ];

        foreach ($brands as $brandData) {
            $brand = VehicleBrand::updateOrCreate(
                ['name' => $brandData['name']],
                [
                    'is_active' => true,
                    'sort_order' => $brandData['sort_order'],
                ],
            );

            foreach ($brandData['models'] as $modelData) {
                VehicleModel::updateOrCreate(
                    ['vehicle_brand_id' => $brand->id, 'name' => $modelData['name']],
                    [
                        'price' => $modelData['price'],
                        'is_active' => true,
                        'sort_order' => array_search($modelData, $brandData['models'], true) + 1,
                    ],
                );
            }
        }
    }
}
