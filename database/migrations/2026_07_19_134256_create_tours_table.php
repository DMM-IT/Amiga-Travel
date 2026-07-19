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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('tour_name');
            $table->string('promo')->nullable();
            $table->string('country')->nullable();
            $table->string('destinations'); // semicolon-separated
            $table->string('duration'); // e.g., "3D2N"
            $table->unsignedInteger('duration_days'); // extracted from duration
            $table->decimal('price_per_pax', 10, 2)->default(0);
            $table->string('airline')->nullable();
            $table->string('origin'); // departure city
            $table->string('destination'); // primary destination
            $table->string('mode'); // 'airline' or 'ferry'
            $table->text('hotel')->nullable();
            $table->text('inclusions')->nullable();
            $table->text('exclusions')->nullable();
            $table->text('highlights')->nullable();
            $table->text('day1')->nullable();
            $table->text('day2')->nullable();
            $table->text('day3')->nullable();
            $table->text('day4')->nullable();
            $table->text('day5')->nullable();
            $table->text('day6')->nullable();
            $table->string('meals')->nullable();
            $table->string('hand_carry')->nullable();
            $table->string('check_in_baggage')->nullable();
            $table->string('tour_guide')->nullable(); // Yes/No
            $table->string('travel_insurance')->nullable();
            $table->text('remarks')->nullable();
            $table->string('image')->nullable(); // URL to tour image
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
        Schema::dropIfExists('tours');
    }
};
