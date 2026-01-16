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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profession')->nullable()->after('financial_goal');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->boolean('show_profession')->default(false)->after('show_avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profession');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('show_profession');
        });
    }
};
