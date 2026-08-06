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
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->renameColumn('fee_per_person', 'web_admin_fee');
            $table->decimal('transaction_fee', 10, 2)->default(345)->after('fee_per_accommodation');
            $table->decimal('revalidation_fee', 10, 2)->default(250)->after('transaction_fee');
        });

        // Set the default value of the renamed column to 175 and update existing rows
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->decimal('web_admin_fee', 10, 2)->default(175)->change();
        });

        \Illuminate\Support\Facades\DB::table('payment_settings')->update([
            'web_admin_fee' => 175,
            'transaction_fee' => 345,
            'revalidation_fee' => 250,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->renameColumn('web_admin_fee', 'fee_per_person');
            $table->dropColumn(['transaction_fee', 'revalidation_fee']);
        });

        Schema::table('payment_settings', function (Blueprint $table) {
            $table->decimal('fee_per_person', 10, 2)->default(2000)->change();
        });
    }
};
