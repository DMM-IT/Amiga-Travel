<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to make service cancellation resume date optional and add resumption tracking.
     */
    public function up(): void
    {
        Schema::table('service_cancellations', function (Blueprint $table) {
            $table->date('resume_date')->nullable()->change();
            if (! Schema::hasColumn('service_cancellations', 'resumed_at')) {
                $table->timestamp('resumed_at')->nullable()->after('resume_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_cancellations', function (Blueprint $table) {
            $table->date('resume_date')->nullable(false)->change();
            if (Schema::hasColumn('service_cancellations', 'resumed_at')) {
                $table->dropColumn('resumed_at');
            }
        });
    }
};
