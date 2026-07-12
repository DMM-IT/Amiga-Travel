<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            if (! Schema::hasColumn('ferry_routes', 'mode')) {
                $table->string('mode')->default('ferry')->after('is_active');
            }

            if (! Schema::hasColumn('ferry_routes', 'operator')) {
                $table->string('operator')->nullable()->after('mode');
            }

            // try to replace the old unique index with a mode/operator-aware unique
            try {
                $table->dropUnique('ferry_routes_origin_destination_unique');
            } catch (\Throwable $e) {
                // ignore if it doesn't exist
            }

            // add the new unique if not present
            try {
                $table->unique(['origin', 'destination', 'mode', 'operator']);
            } catch (\Throwable $e) {
                // ignore duplicate index errors
            }
        });
    }

    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            try {
                $table->dropUnique('ferry_routes_origin_destination_mode_unique');
            } catch (\Throwable $e) {
            }

            try {
                $table->unique(['origin', 'destination']);
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('ferry_routes', 'operator')) {
                $table->dropColumn('operator');
            }

            if (Schema::hasColumn('ferry_routes', 'mode')) {
                $table->dropColumn('mode');
            }
        });
    }
};
