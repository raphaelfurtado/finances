<?php

namespace Tests\Feature\Livewire;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Transactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'description' => 'Buying Coffee'
        ]);

        Livewire::test(Transactions::class)
            ->assertSee('Buying Coffee');
    }

    public function test_it_can_delete_a_transaction()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id
        ]);

        Livewire::test(Transactions::class)
            ->call('deleteTransaction', $transaction->id);

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
