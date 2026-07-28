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
        Schema::table('service_cancellations', function (Blueprint $table) {
            $table->foreignId('ferry_route_id')->nullable()->after('carrier')->constrained('ferry_routes')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->after('ferry_route_id')->constrained('vehicles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_cancellations', function (Blueprint $table) {
            $table->dropForeign(['ferry_route_id']);
            $table->dropColumn('ferry_route_id');
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });
    }
};
