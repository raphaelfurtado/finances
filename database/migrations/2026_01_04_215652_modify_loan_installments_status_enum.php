<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE loan_installments MODIFY COLUMN status ENUM('pending', 'partial', 'paid', 'overdue', 'refinanced') DEFAULT 'pending'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE loan_installments MODIFY COLUMN status ENUM('pending', 'partial', 'paid', 'overdue') DEFAULT 'pending'");
            }
        });
    }
};
