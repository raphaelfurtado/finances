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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('stripe_id')->nullable(); // Helper to match stripe price
            $table->decimal('price', 10, 2);
            $table->string('interval')->default('month'); // month, year
            $table->boolean('active')->default(true);
            $table->boolean('recommended')->default(false);
            $table->timestamps();
        });

        Schema::create('plan_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->timestamps();
        });

        // Seed initial data
        $this->seed();
    }

    private function seed()
    {
        // Insert Premium Plan similar to current hardcoded one
        $planId = DB::table('plans')->insertGetId([
            'name' => 'Premium',
            'slug' => 'premium',
            'stripe_id' => config('cashier.secret') ? 'price_1Q...' : 'price_dummy', // We'll put a placeholder or try to get from config if possible, but config might not be available here reliably in all envs.
            'price' => 9.90,
            'interval' => 'month',
            'active' => true,
            'recommended' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $benefits = [
            'Tudo do Grátis +',
            'Relatórios detalhados com gráficos',
            'Planilhas prontas para exportar',
            'Análise por categoria',
            'Badge Premium no perfil',
        ];

        foreach ($benefits as $benefit) {
            DB::table('plan_benefits')->insert([
                'plan_id' => $planId,
                'description' => $benefit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_benefits');
        Schema::dropIfExists('plans');
    }
};
