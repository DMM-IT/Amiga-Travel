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
        Schema::create('transport_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create pivot table for schedules and transport classes
        Schema::create('schedule_transport_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transport_class_id')->constrained()->cascadeOnDelete();
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->timestamps();
        });

        // Create pivot table for bookings and transport classes
        Schema::create('booking_transport_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transport_class_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_transport_class');
        Schema::dropIfExists('schedule_transport_class');
        Schema::dropIfExists('transport_classes');
    }
};
