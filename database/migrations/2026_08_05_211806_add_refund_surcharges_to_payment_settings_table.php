<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->decimal('ferry_before_departure_surcharge_pct', 5, 2)->default(25)->after('revalidation_fee');
            $table->decimal('ferry_after_departure_surcharge_pct', 5, 2)->default(40)->after('ferry_before_departure_surcharge_pct');
            $table->decimal('airline_before_departure_surcharge_pct', 5, 2)->default(40)->after('ferry_after_departure_surcharge_pct');
        });
    }

    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn([
                'ferry_before_departure_surcharge_pct',
                'ferry_after_departure_surcharge_pct',
                'airline_before_departure_surcharge_pct',
            ]);
        });
    }
};
