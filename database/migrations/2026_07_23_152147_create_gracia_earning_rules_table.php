<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gracia_earning_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('spend_threshold_centavos')->default(100000);
            $table->integer('points_awarded')->default(5);
            $table->boolean('is_active')->default(false);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gracia_earning_rules');
    }
};
