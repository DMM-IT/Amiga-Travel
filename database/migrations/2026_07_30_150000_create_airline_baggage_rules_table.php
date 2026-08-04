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
        Schema::create('airline_baggage_rules', function (Blueprint $table) {
            $table->id();
            $table->string('operator')->index(); // pal, ceb_pac, airasia, etc.
            $table->string('operator_name');     // Philippine Airline, Cebu Pacific, AirAsia
            $table->string('code');              // PAL, Cebu Pacific, AirAsia
            $table->string('logo')->nullable();  // Pal-Logo.jfif, CebuPecific-Logo.png, AirAsia-Logo.png
            $table->string('trip_type')->default('local')->index(); // local or international
            $table->string('weight');            // 15 kg, 20 kg, etc.
            $table->unsignedInteger('weight_kg')->default(0); // 15, 20, etc. for sorting
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airline_baggage_rules');
    }
};
