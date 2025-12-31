<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('late_fee_percentage', 5, 2)->nullable()->after('total_amount');
            $table->decimal('daily_interest_rate', 5, 2)->nullable()->after('late_fee_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['late_fee_percentage', 'daily_interest_rate']);
        });
    }
};
