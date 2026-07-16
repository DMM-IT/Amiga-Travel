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
            $table->boolean('has_vehicle')->default(false)->after('total_price');
            $table->string('vehicle_type')->nullable()->after('has_vehicle');
            $table->string('vehicle_plate_number')->nullable()->after('vehicle_type');
            $table->decimal('vehicle_price', 10, 2)->nullable()->after('vehicle_plate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['has_vehicle', 'vehicle_type', 'vehicle_plate_number', 'vehicle_price']);
        });
    }
};
