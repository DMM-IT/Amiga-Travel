<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['page' => 'header', 'is_active' => true],
            ['page' => 'footer', 'is_active' => true],
            ['page' => 'home', 'is_active' => true],
            ['page' => 'about', 'is_active' => true],
            ['page' => 'gallery', 'is_active' => true],
            ['page' => 'services', 'is_active' => true],
            ['page' => 'tour_package', 'is_active' => true],
            ['page' => 'contact_us', 'is_active' => true],
            ['page' => 'download', 'is_active' => true],
        ];

        foreach ($pages as $page) {
            WebsiteSetting::updateOrCreate(
                ['page' => $page['page']],
                $page
            );
        }
    }
}
