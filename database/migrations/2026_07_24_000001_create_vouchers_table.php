<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Internal name
            $table->string('code')->unique();
            $table->text('description')->nullable(); // Internal notes
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable(); // Only for percentage
            $table->decimal('min_booking_amount', 10, 2)->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('total_usage_limit')->nullable(); // Null = unlimited
            $table->boolean('one_use_per_customer')->default(true);
            $table->enum('eligible_scope', ['ticket_fare', 'booking_total', 'vehicle', 'accommodation'])->default('ticket_fare');
            $table->string('eligible_origin')->nullable();
            $table->string('eligible_destination')->nullable();
            $table->foreignId('eligible_schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->timestamps();
            
            $table->index('code');
            $table->index(['is_active', 'start_at', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
