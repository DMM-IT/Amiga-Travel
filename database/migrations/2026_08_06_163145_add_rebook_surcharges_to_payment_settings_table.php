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
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->decimal('rebook_ferry_before_departure_surcharge_pct', 5, 2)->default(15.00);
            $table->decimal('rebook_ferry_after_departure_surcharge_pct', 5, 2)->default(35.00);
            $table->decimal('rebook_airline_before_departure_surcharge_pct', 5, 2)->default(15.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn([
                'rebook_ferry_before_departure_surcharge_pct',
                'rebook_ferry_after_departure_surcharge_pct',
                'rebook_airline_before_departure_surcharge_pct',
            ]);
        });
    }
};
