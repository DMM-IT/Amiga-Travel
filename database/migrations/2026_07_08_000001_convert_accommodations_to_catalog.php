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
        Schema::table('accommodations', function (Blueprint $table) {
            // Accommodations become an admin-managed catalog instead of
            // free-text rows typed by the client per booking.
            if (Schema::hasColumn('accommodations', 'booking_id')) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            }

            $table->text('description')->nullable()->after('name');
            $table->json('images')->nullable()->after('price');
            $table->boolean('is_active')->default(true)->after('images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['description', 'images', 'is_active']);
            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
