<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('passengers', 'promotional_ticket_id')) {
                $table->foreignId('promotional_ticket_id')
                    ->nullable()
                    ->after('discount_id')
                    ->constrained('promotional_tickets', 'id', 'fk_pax_promo_ticket_id')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('passengers', 'is_promo')) {
                $table->boolean('is_promo')->default(false)->after('promotional_ticket_id');
            }

            if (! Schema::hasColumn('passengers', 'promo_price')) {
                $table->decimal('promo_price', 10, 2)->nullable()->after('is_promo');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'promo_ticket_count')) {
                $table->unsignedInteger('promo_ticket_count')->default(0)->after('promotional_ticket_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            if (Schema::hasColumn('passengers', 'promo_price')) {
                $table->dropColumn('promo_price');
            }
            if (Schema::hasColumn('passengers', 'is_promo')) {
                $table->dropColumn('is_promo');
            }
            if (Schema::hasColumn('passengers', 'promotional_ticket_id')) {
                $table->dropForeign('fk_pax_promo_ticket_id');
                $table->dropColumn('promotional_ticket_id');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'promo_ticket_count')) {
                $table->dropColumn('promo_ticket_count');
            }
        });
    }
};
