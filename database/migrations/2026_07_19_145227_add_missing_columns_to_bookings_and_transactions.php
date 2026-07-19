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
            if (!Schema::hasColumn('bookings', 'tour_id')) {
                $table->foreignId('tour_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'tour_date_id')) {
                $table->foreignId('tour_date_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'tour_inclusions')) {
                $table->text('tour_inclusions')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancellation_fee')) {
                $table->decimal('cancellation_fee', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'refund_destination')) {
                $table->string('refund_destination')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'is_rebooked')) {
                $table->boolean('is_rebooked')->default(false);
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'rebooking_fee')) {
                $table->decimal('rebooking_fee', 10, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->dropForeign(['tour_date_id']);
            $table->dropColumn([
                'tour_id', 
                'tour_date_id', 
                'tour_inclusions', 
                'cancellation_fee', 
                'refund_amount', 
                'refund_destination', 
                'is_rebooked'
            ]);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('rebooking_fee');
        });
    }
};
