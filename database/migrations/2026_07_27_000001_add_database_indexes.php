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
        Schema::table('bookings', function (Blueprint $table): void {
            $table->index(['client_email', 'created_at'], 'bookings_client_email_created_at_index');
            $table->index(['status', 'created_at'], 'bookings_status_created_at_index');
            $table->index('rebooking_status', 'bookings_rebooking_status_index');
            $table->index(['schedule_id', 'departure_date', 'status'], 'bookings_schedule_id_departure_date_status_index');
        });

        Schema::table('transactions', function (Blueprint $table): void {
            $table->index('payment_status', 'transactions_payment_status_index');
        });

        Schema::table('schedules', function (Blueprint $table): void {
            $table->index('departure_time', 'schedules_departure_time_index');
        });

        Schema::table('ferry_routes', function (Blueprint $table): void {
            $table->index(['origin', 'destination', 'mode', 'is_active'], 'ferry_routes_origin_destination_mode_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropIndex('bookings_client_email_created_at_index');
            $table->dropIndex('bookings_status_created_at_index');
            $table->dropIndex('bookings_rebooking_status_index');
            $table->dropIndex('bookings_schedule_id_departure_date_status_index');
        });

        Schema::table('transactions', function (Blueprint $table): void {
            $table->dropIndex('transactions_payment_status_index');
        });

        Schema::table('schedules', function (Blueprint $table): void {
            $table->dropIndex('schedules_departure_time_index');
        });

        Schema::table('ferry_routes', function (Blueprint $table): void {
            $table->dropIndex('ferry_routes_origin_destination_mode_active_index');
        });
    }
};
