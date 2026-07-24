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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('return_schedule_id')->nullable()->after('schedule_id');
            $table->string('return_schedule_service')->nullable()->after('return_schedule_id');
            $table->string('return_schedule_departure_time')->nullable()->after('return_schedule_service');
            $table->string('return_schedule_arrival_time')->nullable()->after('return_schedule_departure_time');
            $table->decimal('return_schedule_price', 10, 2)->nullable()->after('return_schedule_arrival_time');
            $table->unsignedBigInteger('return_schedule_accommodation_id')->nullable()->after('return_schedule_price');
            $table->string('return_schedule_accommodation_name')->nullable()->after('return_schedule_accommodation_id');
            $table->decimal('return_schedule_accommodation_price', 10, 2)->nullable()->after('return_schedule_accommodation_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'return_schedule_id',
                'return_schedule_service',
                'return_schedule_departure_time',
                'return_schedule_arrival_time',
                'return_schedule_price',
                'return_schedule_accommodation_id',
                'return_schedule_accommodation_name',
                'return_schedule_accommodation_price',
            ]);
        });
    }
};
