<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('return_date')->constrained()->nullOnDelete();
            $table->string('schedule_service')->nullable()->after('schedule_id');
            $table->string('schedule_departure_time')->nullable()->after('schedule_service');
            $table->string('schedule_arrival_time')->nullable()->after('schedule_departure_time');
            $table->decimal('schedule_price', 10, 2)->nullable()->after('schedule_arrival_time');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_id');
            $table->dropColumn([
                'schedule_service',
                'schedule_departure_time',
                'schedule_arrival_time',
                'schedule_price',
            ]);
        });
    }
};
