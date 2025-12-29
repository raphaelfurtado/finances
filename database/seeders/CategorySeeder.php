<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();

        if (!$user) {
            return;
        }

        $categories = [
            [
                'name' => 'Alimentação',
                'description' => 'Restaurantes, Mercado, Ifood',
                'icon' => 'utensils',
                'color' => '#FB923C',
                'user_id' => $user->id
            ],
            [
                'name' => 'Transporte',
                'description' => 'Uber, Combustível, Ônibus',
                'icon' => 'bus',
                'color' => '#60A5FA',
                'user_id' => $user->id
            ],
            [
                'name' => 'Moradia',
                'description' => 'Aluguel, Luz, Internet',
                'icon' => 'home',
                'color' => '#C084FC',
                'user_id' => $user->id
            ],
            [
                'name' => 'Lazer',
                'description' => 'Cinema, Jogos, Viagens',
                'icon' => 'gamepad-2',
                'color' => '#F472B6',
                'user_id' => $user->id
            ],
            [
                'name' => 'Saúde',
                'description' => 'Farmácia, Consultas',
                'icon' => 'heart-pulse',
                'color' => '#34D399',
                'user_id' => $user->id
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
