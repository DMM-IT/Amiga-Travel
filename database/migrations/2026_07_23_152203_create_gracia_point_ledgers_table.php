<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gracia_point_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gracia_earning_rule_id')->nullable()->constrained('gracia_earning_rules')->nullOnDelete();
            $table->integer('points');
            $table->string('entry_type'); // earned, reversed, admin_adjustment
            $table->integer('qualifying_spend_centavos')->default(0);
            $table->string('reason')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('idempotency_key')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gracia_point_ledgers');
    }
};
