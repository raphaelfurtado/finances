<?php

namespace Tests\Unit\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_filter_by_type()
    {
        Transaction::factory()->count(3)->create(['type' => 'income']);
        Transaction::factory()->count(2)->create(['type' => 'expense']);

        $this->assertEquals(3, Transaction::where('type', 'income')->count());
        $this->assertEquals(2, Transaction::where('type', 'expense')->count());
    }

    public function test_it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
    }
}
