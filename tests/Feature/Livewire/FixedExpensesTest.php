<?php

namespace Tests\Feature\Livewire;

use App\Models\Category;
use App\Models\FixedExpense;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\FixedExpenses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class FixedExpensesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_fixed_expense_with_current_month()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create(['user_id' => $user->id]);

        Livewire::test(FixedExpenses::class)
            ->set('title', 'Internet')
            ->set('amount', 100)
            ->set('due_day', 10)
            ->set('category_id', $category->id)
            ->set('type', 'expense')
            ->set('start_month', 'current')
            ->call('save');

        $this->assertDatabaseHas('fixed_expenses', [
            'title' => 'Internet',
            'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00')
        ]);
    }

    public function test_it_creates_fixed_expense_with_next_month()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create(['user_id' => $user->id]);

        Livewire::test(FixedExpenses::class)
            ->set('title', 'Aluguel')
            ->set('amount', 1200)
            ->set('due_day', 5)
            ->set('category_id', $category->id)
            ->set('type', 'expense')
            ->set('start_month', 'next')
            ->call('save');

        $this->assertDatabaseHas('fixed_expenses', [
            'title' => 'Aluguel',
            'start_date' => Carbon::now()->addMonth()->startOfMonth()->format('Y-m-d 00:00:00')
        ]);
    }

    public function test_mark_as_paid_now_is_hidden_when_next_month_is_selected()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(FixedExpenses::class)
            ->call('openModal')
            ->set('start_month', 'current')
            ->assertSee('paguei')
            ->set('start_month', 'next')
            ->assertDontSee('paguei');
    }
}
