<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('name');
            $table->text('id_image_front')->nullable()->after('id_number');
            $table->text('id_image_back')->nullable()->after('id_image_front');
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn(['birthdate', 'id_image_front', 'id_image_back']);
        });
    }
};
