<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('schedule_accommodation_id')->nullable()->after('schedule_price')->constrained()->nullOnDelete();
            $table->string('schedule_accommodation_name')->nullable()->after('schedule_accommodation_id');
            $table->decimal('schedule_accommodation_price', 10, 2)->nullable()->after('schedule_accommodation_name');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_accommodation_id');
            $table->dropColumn(['schedule_accommodation_name', 'schedule_accommodation_price']);
        });
    }
};
