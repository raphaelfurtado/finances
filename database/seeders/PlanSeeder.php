<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // Check if plans already exist to avoid duplicates
        if (Plan::count() > 0) {
            // If plans exist but no free plan, let's add it
            if (Plan::where('price', 0)->count() === 0) {
                $this->createFreePlan();
            }
            return;
        }

        $this->createFreePlan();

        $plan = Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'stripe_id' => config('services.stripe.premium_price_id', 'price_dummy'),
            'price' => 990, // Stored in cents
            'interval' => 'month',
            'active' => true,
            'recommended' => true,
        ]);

        $plan->benefits()->createMany([
            ['description' => 'Acesso ilimitado a todas as funcionalidades'],
            ['description' => 'Suporte prioritário'],
            ['description' => 'Sem anúncios'],
            ['description' => 'Relatórios avançados'],
            ['description' => 'Exportação de dados'],
        ]);
    }

    private function createFreePlan()
    {
        $freePlan = Plan::create([
            'name' => 'Plano Grátis',
            'slug' => 'free',
            'stripe_id' => 'free',
            'price' => 0,
            'interval' => 'month',
            'active' => true,
            'recommended' => false,
        ]);

        $freePlan->benefits()->createMany([
            ['description' => 'Dashboard financeiro'],
            ['description' => 'Transações ilimitadas'],
            ['description' => 'Gerenciamento de categorias'],
        ]);
    }
}
