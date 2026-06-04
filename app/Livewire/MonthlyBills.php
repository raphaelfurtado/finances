<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\FixedExpense;
use Carbon\Carbon;

class MonthlyBills extends Component
{
    #[Computed]
    public function criticalExpenses()
    {
        $now = Carbon::now();

        return FixedExpense::where('user_id', auth()->id())
            ->where('is_active', true)
            ->with(['transactions' => fn($q) => $q->whereMonth('date', $now->month)->whereYear('date', $now->year)])
            ->get()
            ->filter(fn($expense) => in_array($expense->status_this_month, ['overdue', 'due_soon']));
    }

    public function render()
    {
        $criticalExpenses = $this->criticalExpenses;

        return view('livewire.monthly-bills', [
            'criticalCount' => $criticalExpenses->count(),
            'overdueCount' => $criticalExpenses->where('status_this_month', 'overdue')->count(),
        ]);
    }
}