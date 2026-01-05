<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FixedExpense;
use Carbon\Carbon;

class MonthlyBills extends Component
{
    public function getCriticalExpensesProperty()
    {
        return FixedExpense::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get()
            ->filter(fn($expense) => in_array($expense->status_this_month, ['overdue', 'due_soon']));
    }

    public function render()
    {
        $criticalExpenses = $this->criticalExpenses;
        $criticalCount = $criticalExpenses->count();
        $overdueCount = $criticalExpenses->where('status_this_month', 'overdue')->count();

        return view('livewire.monthly-bills', [
            'criticalCount' => $criticalCount,
            'overdueCount' => $overdueCount
        ]);
    }
}
