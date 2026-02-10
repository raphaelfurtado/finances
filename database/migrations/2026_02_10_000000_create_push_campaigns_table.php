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
        Schema::create('push_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->text('body');
            $table->enum('target_segment', ['all', 'free', 'premium', 'test_user']);
            $table->integer('sent_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_campaigns');
    }
};
