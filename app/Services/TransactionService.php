<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\FixedExpense;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionService
{
    /**
     * Get transactions for a specific month, merging real transactions with active fixed expenses (forecast).
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public function getTransactionsForMonth($userId, $month, $year)
    {
        // 1. Fetch Real Transactions
        $realTransactions = Transaction::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with('category.parent') // Eager load category
            ->get();

        // 2. Fetch Active Fixed Expenses
        $fixedExpenses = FixedExpense::where('user_id', $userId)
            ->where('is_active', true)
            ->with('category')
            ->get();

        // 3. IDs of fixed expenses that already have a real transaction this month
        $processedFixedExpenseIds = $realTransactions
            ->whereNotNull('fixed_expense_id')
            ->pluck('fixed_expense_id')
            ->toArray();

        $mergedList = collect($realTransactions);

        // CHECK: Only show Forecast (Ghost Transactions) for Current Month or Future
        $viewDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $currentDate = now()->startOfMonth();

        if ($viewDate->lt($currentDate)) {
            // Past month: Return only real transactions (History)
            return $mergedList->sortByDesc('date');
        }

        // 4. Create "Ghost" transactions for missing fixed expenses
        foreach ($fixedExpenses as $fixed) {
            if (in_array($fixed->id, $processedFixedExpenseIds)) {
                continue; // Already paid/recorded
            }

            // Create a temporary object or modify the model to look like a transaction
            // We can use a plain object or a transient model instance
            $ghost = new Transaction([
                'id' => 'ghost_' . $fixed->id, // Virtual ID
                'user_id' => $userId,
                'category_id' => $fixed->category_id,
                'amount' => $fixed->amount,
                'description' => $fixed->title,
                'date' => Carbon::createFromDate($year, $month, $fixed->due_day),
                'type' => $fixed->type,
                'fixed_expense_id' => $fixed->id,
            ]);

            // Manually set relationships/attributes that might be missing
            $ghost->setRelation('category', $fixed->category);
            $ghost->is_projected = true; // Flag for frontend
            $ghost->original_fixed_id = $fixed->id; // Helper validation

            $mergedList->push($ghost);
        }

        // 5. Sort by date desc (or asc depending on preference, usually desc for timeline)
        return $mergedList->sortByDesc('date');
    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data)
    {
        return Transaction::create($data);
    }

    /**
     * Register a payment for a fixed expense in the given month.
     * Returns the created transaction, or null if it was already paid this month.
     */
    public function payFixedExpense(FixedExpense $expense, ?float $amount = null, ?Carbon $date = null): ?Transaction
    {
        $date = $date ?? Carbon::now();

        $alreadyPaid = Transaction::where('fixed_expense_id', $expense->id)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->exists();

        if ($alreadyPaid) {
            return null;
        }

        return Transaction::create([
            'user_id' => $expense->user_id,
            'category_id' => $expense->category_id,
            'type' => $expense->type,
            'amount' => $amount ?? $expense->amount,
            'description' => 'Pgto: ' . $expense->title,
            'date' => $date,
            'payment_method' => 'other',
            'status' => 'paid',
            'is_recurring' => true,
            'fixed_expense_id' => $expense->id,
        ]);
    }
}
