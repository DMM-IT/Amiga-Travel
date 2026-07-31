<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table): void {
            if (! Schema::hasColumn('transactions', 'student_discount_proofs')) {
                $table->json('student_discount_proofs')->nullable()->after('rebooking_proof_of_payment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table): void {
            if (Schema::hasColumn('transactions', 'student_discount_proofs')) {
                $table->dropColumn('student_discount_proofs');
            }
        });
    }
};
