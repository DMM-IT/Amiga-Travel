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
        Schema::table('passengers', function (Blueprint $table) {
            $table->string('seat_number')->nullable()->after('discount_id');
            $table->string('seat_row')->nullable()->after('seat_number');
            $table->string('seat_section')->nullable()->after('seat_row'); // e.g., "Economy", "Business"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn(['seat_number', 'seat_row', 'seat_section']);
        });
    }
};
