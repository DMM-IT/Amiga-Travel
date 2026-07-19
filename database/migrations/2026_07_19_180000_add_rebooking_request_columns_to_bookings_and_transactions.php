<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'rebooking_status')) {
                $table->string('rebooking_status')->nullable()->after('is_rebooked');
            }

            if (! Schema::hasColumn('bookings', 'rebooking_departure_date')) {
                $table->date('rebooking_departure_date')->nullable()->after('rebooking_status');
            }

            if (! Schema::hasColumn('bookings', 'rebooking_return_date')) {
                $table->date('rebooking_return_date')->nullable()->after('rebooking_departure_date');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'rebooking_proof_of_payment')) {
                $table->string('rebooking_proof_of_payment')->nullable()->after('rebooking_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'rebooking_status')) {
                $table->dropColumn('rebooking_status');
            }

            if (Schema::hasColumn('bookings', 'rebooking_departure_date')) {
                $table->dropColumn('rebooking_departure_date');
            }

            if (Schema::hasColumn('bookings', 'rebooking_return_date')) {
                $table->dropColumn('rebooking_return_date');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'rebooking_proof_of_payment')) {
                $table->dropColumn('rebooking_proof_of_payment');
            }
        });
    }
};
