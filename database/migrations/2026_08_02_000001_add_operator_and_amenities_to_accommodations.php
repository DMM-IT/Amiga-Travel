<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->string('operator')->nullable()->after('destination')->comment('Ferry operator (2GO, Starlite, etc.)');
            $table->text('amenities')->nullable()->after('description')->comment('Comma-separated amenities');
        });
    }

    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['operator', 'amenities']);
        });
    }
};
