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
        Schema::table('booking_transport_class', function (Blueprint $table) {
            $table->boolean('is_promo')->default(false)->after('price');
            $table->string('rate_code')->nullable()->after('is_promo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_transport_class', function (Blueprint $table) {
            $table->dropColumn(['is_promo', 'rate_code']);
        });
    }
};
