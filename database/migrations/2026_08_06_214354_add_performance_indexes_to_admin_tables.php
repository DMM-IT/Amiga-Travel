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
            $table->index('created_at', 'idx_bookings_created_at');
            $table->index('status', 'idx_bookings_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('created_at', 'idx_transactions_created_at');
        });

        if (Schema::hasTable('inquiries')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->index('created_at', 'idx_inquiries_created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_created_at');
            $table->dropIndex('idx_bookings_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_created_at');
        });

        if (Schema::hasTable('inquiries')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->dropIndex('idx_inquiries_created_at');
            });
        }
    }
};
