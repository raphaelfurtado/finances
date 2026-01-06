<?php

namespace App\Observers;

use App\Models\Budget;
use App\Models\Transaction;
use App\Notifications\BudgetWarning;
use Carbon\Carbon;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // Só verificamos se for uma despesa e tiver categoria
        if ($transaction->type !== 'expense' || !$transaction->category_id) {
            return;
        }

        // Buscar orçamento para esta categoria e usuário
        $budget = Budget::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->first();

        if (!$budget || $budget->amount <= 0) {
            return;
        }

        // Somar gastos do mês atual para esta categoria
        $totalSpent = Transaction::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->where('type', 'expense')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $usagePercentage = ($totalSpent / $budget->amount) * 100;

        // Se passar de 90%
        if ($usagePercentage > 90) {
            $transaction->user->notify(new BudgetWarning($usagePercentage, $transaction->category->name));
        }
    }
}
