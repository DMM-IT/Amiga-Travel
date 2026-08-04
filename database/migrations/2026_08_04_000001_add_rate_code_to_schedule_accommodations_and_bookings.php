<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedule_accommodations', function (Blueprint $table) {
            $table->string('rate_code')->nullable()->after('name');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('schedule_accommodation_rate_code')->nullable()->after('schedule_accommodation_price');
            $table->string('return_schedule_accommodation_rate_code')->nullable()->after('return_schedule_accommodation_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_accommodations', function (Blueprint $table) {
            $table->dropColumn('rate_code');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['schedule_accommodation_rate_code', 'return_schedule_accommodation_rate_code']);
        });
    }
};
