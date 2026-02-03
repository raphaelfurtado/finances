<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\FixedExpense;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixedExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_not_started_status_for_future_start_date()
    {
        $fixedExpense = FixedExpense::factory()->create([
            'start_date' => Carbon::now()->addMonth()->startOfMonth(),
            'due_day' => 15
        ]);

        $this->assertEquals('not_started', $fixedExpense->status_this_month);
    }

    public function test_it_returns_paid_status_if_transaction_exists_this_month()
    {
        $fixedExpense = FixedExpense::factory()->create([
            'start_date' => Carbon::now()->startOfMonth(),
            'due_day' => 15
        ]);

        Transaction::factory()->create([
            'fixed_expense_id' => $fixedExpense->id,
            'user_id' => $fixedExpense->user_id,
            'date' => Carbon::now()
        ]);

        $this->assertEquals('paid', $fixedExpense->status_this_month);
    }

    public function test_it_returns_overdue_status_if_past_due_day_without_payment()
    {
        Carbon::setTestNow(Carbon::create(2026, 1, 20));

        $fixedExpense = FixedExpense::factory()->create([
            'start_date' => Carbon::create(2026, 1, 1),
            'due_day' => 15
        ]);

        $this->assertEquals('overdue', $fixedExpense->status_this_month);

        Carbon::setTestNow();
    }

    public function test_it_returns_due_soon_status_if_within_two_days()
    {
        Carbon::setTestNow(Carbon::create(2026, 1, 13));

        $fixedExpense = FixedExpense::factory()->create([
            'start_date' => Carbon::create(2026, 1, 1),
            'due_day' => 15
        ]);

        $this->assertEquals('due_soon', $fixedExpense->status_this_month);

        Carbon::setTestNow();
    }

    public function test_it_returns_pending_status_otherwise()
    {
        Carbon::setTestNow(Carbon::create(2026, 1, 5));

        $fixedExpense = FixedExpense::factory()->create([
            'start_date' => Carbon::create(2026, 1, 1),
            'due_day' => 15
        ]);

        $this->assertEquals('pending', $fixedExpense->status_this_month);

        Carbon::setTestNow();
    }
}
