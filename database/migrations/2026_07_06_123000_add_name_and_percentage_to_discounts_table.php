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
        Schema::table('discounts', function (Blueprint $table) {
            if (! Schema::hasColumn('discounts', 'name')) {
                $table->string('name')->default('')->after('id');
            }
            if (! Schema::hasColumn('discounts', 'percentage')) {
                $table->decimal('percentage', 5, 2)->default(0)->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            if (Schema::hasColumn('discounts', 'percentage')) {
                $table->dropColumn('percentage');
            }
            if (Schema::hasColumn('discounts', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
