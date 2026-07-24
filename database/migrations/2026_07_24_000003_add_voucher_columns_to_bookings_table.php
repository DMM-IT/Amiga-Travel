<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->after('promotional_ticket_id')->constrained()->nullOnDelete();
            $table->string('voucher_code')->nullable();
            $table->decimal('voucher_discount_amount', 10, 2)->nullable();
            $table->decimal('subtotal_before_voucher', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voucher_id');
            $table->dropColumn(['voucher_code', 'voucher_discount_amount', 'subtotal_before_voucher']);
        });
    }
};
