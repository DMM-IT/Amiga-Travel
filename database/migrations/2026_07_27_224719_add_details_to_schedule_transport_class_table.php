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
        Schema::table('schedule_transport_class', function (Blueprint $table) {
            $table->text('description')->nullable()->after('transport_class_id');
            $table->boolean('has_bed')->default(false)->after('tickets_available');
            $table->boolean('is_active')->default(true)->after('has_bed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_transport_class', function (Blueprint $table) {
            $table->dropColumn(['description', 'has_bed', 'is_active']);
        });
    }
};
