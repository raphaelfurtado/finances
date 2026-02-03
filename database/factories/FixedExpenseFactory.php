<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FixedExpense>
 */
class FixedExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'type' => 'expense',
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'due_day' => $this->faker->numberBetween(1, 28),
            'is_active' => true,
            'start_date' => now()->startOfMonth(),
        ];
    }
}
