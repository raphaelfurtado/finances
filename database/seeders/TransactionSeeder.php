<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $categories = Category::where('user_id', $user->id)->get();

        if ($categories->isEmpty()) {
            return;
        }

        $alimentacao = $categories->where('name', 'Alimentação')->first();
        $transporte = $categories->where('name', 'Transporte')->first();
        $lazer = $categories->where('name', 'Lazer')->first();

        $transactions = [
            [
                'user_id' => $user->id,
                'category_id' => $alimentacao?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 124.50,
                'description' => 'Supermercado Silva',
                'date' => Carbon::parse('2023-10-24'),
                'payment_method' => 'credit',
                'status' => 'paid',
                'is_recurring' => false,
                'observation' => null,
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categories->first()->id,
                'type' => 'income',
                'amount' => 850.00,
                'description' => 'Pagamento Freelance',
                'date' => Carbon::parse('2023-10-23'),
                'payment_method' => 'pix',
                'status' => 'paid',
                'is_recurring' => false,
                'observation' => 'Projeto Design',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $lazer?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 15.99,
                'description' => 'Assinatura Streaming',
                'date' => Carbon::parse('2023-10-21'),
                'payment_method' => 'credit',
                'status' => 'paid',
                'is_recurring' => true,
                'observation' => 'Entretenimento',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $transporte?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 24.30,
                'description' => 'Corrida App',
                'date' => Carbon::parse('2023-10-20'),
                'payment_method' => 'debit',
                'status' => 'paid',
                'is_recurring' => false,
                'observation' => 'Transporte',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $alimentacao?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 45.90,
                'description' => 'Cafeteria',
                'date' => Carbon::parse('2023-10-18'),
                'payment_method' => 'credit',
                'status' => 'paid',
                'is_recurring' => false,
                'observation' => 'Almoço de confraternização com a equipe do projeto após a entrega do sprint.',
            ],
            // Adicionar mais transações para o mês atual
            [
                'user_id' => $user->id,
                'category_id' => $alimentacao?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 89.90,
                'description' => 'Restaurante Italiano',
                'date' => Carbon::now()->subDays(2),
                'payment_method' => 'credit',
                'status' => 'paid',
                'is_recurring' => false,
            ],
            [
                'user_id' => $user->id,
                'category_id' => $transporte?->id ?? $categories->first()->id,
                'type' => 'expense',
                'amount' => 150.00,
                'description' => 'Combustível',
                'date' => Carbon::now()->subDays(5),
                'payment_method' => 'debit',
                'status' => 'paid',
                'is_recurring' => false,
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categories->first()->id,
                'type' => 'income',
                'amount' => 3500.00,
                'description' => 'Salário',
                'date' => Carbon::now()->subDays(10),
                'payment_method' => 'pix',
                'status' => 'paid',
                'is_recurring' => true,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
