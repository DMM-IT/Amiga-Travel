<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to eliminate full joins and table scans.
     */
    public function up(): void
    {
        $safeAddIndex = function (string $table, array $columns, string $name) {
            try {
                if (Schema::hasTable($table)) {
                    $allColsExist = true;
                    foreach ($columns as $col) {
                        if (! Schema::hasColumn($table, $col)) {
                            $allColsExist = false;
                            break;
                        }
                    }
                    if ($allColsExist) {
                        Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
                    }
                }
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        };

        // 1. Pivot Table: schedule_transport_class
        $safeAddIndex('schedule_transport_class', ['schedule_id', 'transport_class_id'], 'idx_stc_schedule_class');
        $safeAddIndex('schedule_transport_class', ['schedule_id', 'tickets_available'], 'idx_stc_schedule_tickets');

        // 2. Pivot Table: booking_transport_class
        $safeAddIndex('booking_transport_class', ['booking_id', 'transport_class_id'], 'idx_btc_booking_class');

        // 3. Table: schedule_accommodations
        $safeAddIndex('schedule_accommodations', ['schedule_id', 'is_active', 'tickets_available'], 'idx_sa_schedule_active_tickets');
        $safeAddIndex('schedule_accommodations', ['schedule_id', 'is_active'], 'idx_sa_schedule_active');

        // 4. Pivot Table: accommodation_booking
        $safeAddIndex('accommodation_booking', ['booking_id', 'accommodation_id'], 'idx_ab_booking_acc');

        // 5. Table: schedules
        $safeAddIndex('schedules', ['ferry_route_id', 'is_active', 'departure_time'], 'idx_schedules_route_active_time');
        $safeAddIndex('schedules', ['is_active', 'tickets_available'], 'idx_schedules_active_tickets');

        // 6. Table: ferry_routes
        $safeAddIndex('ferry_routes', ['origin', 'destination', 'is_active'], 'idx_routes_origin_dest_active');
        $safeAddIndex('ferry_routes', ['is_active', 'operator'], 'idx_routes_active_operator');

        // 7. Table: bookings
        $safeAddIndex('bookings', ['transaction_number'], 'idx_bookings_tx_number');
        $safeAddIndex('bookings', ['user_id', 'created_at'], 'idx_bookings_user_created');
        $safeAddIndex('bookings', ['client_email', 'status'], 'idx_bookings_email_status');

        // 8. Table: passengers
        $safeAddIndex('passengers', ['booking_id'], 'idx_passengers_booking');
        $safeAddIndex('passengers', ['discount_id'], 'idx_passengers_discount');

        // 9. Table: transactions
        $safeAddIndex('transactions', ['booking_id', 'payment_status'], 'idx_tx_booking_status');

        // 10. Table: tour_dates
        $safeAddIndex('tour_dates', ['tour_id', 'date'], 'idx_tour_dates_tour_date');

        // 11. Table: vouchers
        $safeAddIndex('vouchers', ['code', 'is_active'], 'idx_vouchers_code_active');

        // 12. Table: user_hidden_vouchers
        $safeAddIndex('user_hidden_vouchers', ['user_id', 'voucher_id'], 'idx_uhv_user_voucher');

        // 13. Table: voucher_redemptions
        $safeAddIndex('voucher_redemptions', ['voucher_id', 'user_id'], 'idx_vr_voucher_user');

        // 14. Table: gracia_point_ledgers
        $safeAddIndex('gracia_point_ledgers', ['user_id', 'created_at'], 'idx_gpl_user_created');

        // 15. Table: admin_notification_statuses
        $safeAddIndex('admin_notification_statuses', ['user_id', 'notification_id'], 'idx_ans_user_notification');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
