<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE website_settings MODIFY COLUMN `page` ENUM('header','footer','home','about','gallery','services','tour_package','schedules','contact_us','download','faqs') DEFAULT 'home'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE website_settings MODIFY COLUMN `page` ENUM('header','footer','home','about','gallery','services','tour_package','schedules','contact_us','download') DEFAULT 'home'");
    }
};
