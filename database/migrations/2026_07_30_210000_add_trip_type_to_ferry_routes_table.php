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
        Schema::table('ferry_routes', function (Blueprint $table) {
            if (! Schema::hasColumn('ferry_routes', 'trip_type')) {
                $table->string('trip_type')->default('local')->nullable()->after('mode');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            if (Schema::hasColumn('ferry_routes', 'trip_type')) {
                $table->dropColumn('trip_type');
            }
        });
    }
};
