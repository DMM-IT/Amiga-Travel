<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('transactions', 'confirmation_pdf')) {
            Schema::table('transactions', function (Blueprint $table): void {
                $table->string('confirmation_pdf')->nullable()->after('confirmation_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'confirmation_pdf')) {
            Schema::table('transactions', function (Blueprint $table): void {
                $table->dropColumn('confirmation_pdf');
            });
        }
    }
};