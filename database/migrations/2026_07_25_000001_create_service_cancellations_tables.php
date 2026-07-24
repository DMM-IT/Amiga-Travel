<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cancellation_replacements');
        Schema::dropIfExists('service_cancellations');

        Schema::create('service_cancellations', function (Blueprint $table) {
            $table->id();
            $table->string('cancellation_code')->unique();
            $table->string('service_type'); // 'airline' or 'ferry'
            $table->string('carrier');
            $table->string('scope'); // 'specific_schedule', 'carrier_date', 'carrier_date_range'
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->date('affected_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('reason_category'); // 'weather', 'storm', 'carrier_cancellation', 'safety_issue', 'other'
            $table->text('internal_notes')->nullable();
            $table->text('customer_message');
            $table->date('resume_date');
            $table->string('status')->default('active'); // 'active', 'resolved', 'cancelled'
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cancellation_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_cancellation_id')->constrained('service_cancellations', 'id', 'fk_cnl_rep_cancellation_id')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules', 'id', 'fk_cnl_rep_schedule_id')->cascadeOnDelete();
            $table->date('replacement_date');
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'service_cancellation_id')) {
                $table->foreignId('service_cancellation_id')->nullable()->after('status')->constrained('service_cancellations', 'id', 'fk_bk_cnl_id')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'disruption_status')) {
                $table->string('disruption_status')->nullable()->after('service_cancellation_id');
            }
            if (! Schema::hasColumn('bookings', 'preferred_replacement_schedule_id')) {
                $table->foreignId('preferred_replacement_schedule_id')->nullable()->after('disruption_status')->constrained('schedules', 'id', 'fk_bk_pref_sch_id')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'preferred_replacement_date')) {
                $table->date('preferred_replacement_date')->nullable()->after('preferred_replacement_schedule_id');
            }
            if (! Schema::hasColumn('bookings', 'disruption_notes')) {
                $table->text('disruption_notes')->nullable()->after('preferred_replacement_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'disruption_notes')) {
                $table->dropColumn('disruption_notes');
            }
            if (Schema::hasColumn('bookings', 'preferred_replacement_date')) {
                $table->dropColumn('preferred_replacement_date');
            }
            if (Schema::hasColumn('bookings', 'preferred_replacement_schedule_id')) {
                $table->dropForeign('fk_bk_pref_sch_id');
                $table->dropColumn('preferred_replacement_schedule_id');
            }
            if (Schema::hasColumn('bookings', 'disruption_status')) {
                $table->dropColumn('disruption_status');
            }
            if (Schema::hasColumn('bookings', 'service_cancellation_id')) {
                $table->dropForeign('fk_bk_cnl_id');
                $table->dropColumn('service_cancellation_id');
            }
        });

        Schema::dropIfExists('cancellation_replacements');
        Schema::dropIfExists('service_cancellations');
    }
};
