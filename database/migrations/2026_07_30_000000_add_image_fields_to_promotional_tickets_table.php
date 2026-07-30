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
        Schema::table('promotional_tickets', function (Blueprint $table) {
            $table->string('landscape_image')->nullable()->after('promo_price');
            $table->string('portrait_image')->nullable()->after('landscape_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotional_tickets', function (Blueprint $table) {
            $table->dropColumn(['landscape_image', 'portrait_image']);
        });
    }
};
