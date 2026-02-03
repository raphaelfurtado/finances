<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_transaction()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $service = new TransactionService();

        $data = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 100.50,
            'description' => 'Test transaction',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'pix',
            'status' => 'paid'
        ];

        $transaction = $service->create($data);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => 100.50,
            'description' => 'Test transaction'
        ]);
    }
}
