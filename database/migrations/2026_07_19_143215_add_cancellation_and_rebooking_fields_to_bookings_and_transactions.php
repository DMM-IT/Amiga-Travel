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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('cancellation_fee', 10, 2)->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_destination')->nullable();
            $table->boolean('is_rebooked')->default(false);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('rebooking_fee', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancellation_fee', 'refund_amount', 'refund_destination', 'is_rebooked']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['rebooking_fee']);
        });
    }
};
