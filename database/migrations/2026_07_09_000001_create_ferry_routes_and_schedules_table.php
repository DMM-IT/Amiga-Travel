<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ferry_routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->string('destination');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['origin', 'destination']);
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ferry_route_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->decimal('price', 10, 2);
            $table->json('operating_days');
            $table->string('availability_label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('ferry_routes');
    }
};
