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
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['verified_by_user_id']);
            $table->dropColumn(['verified_by_user_id', 'verified_at']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['verified_by_user_id']);
            $table->dropColumn(['verified_by_user_id', 'verified_at']);
        });
    }
};
