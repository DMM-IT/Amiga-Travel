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
            $table->string('return_seat_number')->nullable()->after('seat_section');
            $table->string('return_seat_row')->nullable()->after('return_seat_number');
            $table->string('return_seat_section')->nullable()->after('return_seat_row');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn(['return_seat_number', 'return_seat_row', 'return_seat_section']);
        });
    }
};
