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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['receivable', 'payable']);
            $table->string('counterparty_name');
            $table->string('counterparty_phone')->nullable();
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->integer('installments_count');
            $table->date('start_date');
            $table->enum('status', ['active', 'paid', 'defaulted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
