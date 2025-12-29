<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class CategoryTemplateService
{
    public static function applyPersonalFinancesTemplate(User $user)
    {
        $categories = [
            [
                'name' => 'Alimentação',
                'description' => 'Mercado, restaurantes, delivery',
                'color' => '#EF4444', // Red
                'icon' => 'utensils',
            ],
            [
                'name' => 'Moradia',
                'description' => 'Aluguel, condomínio, luz, água',
                'color' => '#3B82F6', // Blue
                'icon' => 'home',
            ],
            [
                'name' => 'Transporte',
                'description' => 'Combustível, uber, ônibus',
                'color' => '#F59E0B', // Amber
                'icon' => 'car',
            ],
            [
                'name' => 'Saúde',
                'description' => 'Farmácia, convênio, consultas',
                'color' => '#10B981', // Green
                'icon' => 'heart',
            ],
            [
                'name' => 'Lazer',
                'description' => 'Cinema, viagens, hobbies',
                'color' => '#8B5CF6', // Purple
                'icon' => 'gamepad-2',
            ],
            [
                'name' => 'Salário',
                'description' => 'Salário mensal, renda extra',
                'color' => '#22C55E', // Green
                'icon' => 'wallet',
            ],
            [
                'name' => 'Educação',
                'description' => 'Faculdade, cursos, livros',
                'color' => '#6366F1', // Indigo
                'icon' => 'book',
            ],
            [
                'name' => 'Outros',
                'description' => 'Despesas diversas',
                'color' => '#6B7280', // Gray
                'icon' => 'circle-dashed',
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['user_id' => $user->id, 'name' => $data['name']],
                $data
            );
        }
    }
}
