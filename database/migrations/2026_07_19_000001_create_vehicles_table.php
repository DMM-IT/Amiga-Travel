<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ferry', 'airline']); // Ferry or Airline
            $table->string('name'); // Vehicle name (e.g., "MV Superferry 16", "Philippine Airlines PR123")
            $table->string('vehicle_id')->unique(); // Unique identifier
            $table->string('operator'); // Operating company
            $table->text('description')->nullable(); // Additional details
            $table->integer('capacity')->nullable(); // Passenger capacity
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });

        // Add vehicle_id foreign key to ferry_routes table
        Schema::table('ferry_routes', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            $table->dropForeignIdFor('vehicles');
        });

        Schema::dropIfExists('vehicles');
    }
};
