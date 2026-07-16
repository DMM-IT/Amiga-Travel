<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transport_classes', function (Blueprint $table) {
            $table->string('operator')->nullable()->after('id');
            $table->string('code')->nullable()->after('operator');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('transport_classes', function (Blueprint $table) {
            $table->dropColumn(['operator', 'code', 'sort_order']);
        });
    }
};
