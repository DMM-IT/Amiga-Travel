<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bookings', 'cancellation_window_expires_at')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->dateTime('cancellation_window_expires_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'cancellation_window_expires_at')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->dropColumn('cancellation_window_expires_at');
            });
        }
    }
};