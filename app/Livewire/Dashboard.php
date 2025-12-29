<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $income = 0;
    public $expenses = 0;
    public $balance = 0;
    public $incomeChange = 0;
    public $expenseChange = 0;
    public $recentTransactions = [];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $userId = auth()->id();

        // Calcular receitas do mês
        $this->income = Transaction::where('user_id', $userId)
            ->income()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->sum('amount');

        // Calcular despesas do mês
        $this->expenses = Transaction::where('user_id', $userId)
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->sum('amount');

        // Calcular saldo líquido
        $this->balance = $this->income - $this->expenses;

        // Calcular mudanças percentuais (mês anterior)
        $previousMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();

        $previousIncome = Transaction::where('user_id', $userId)
            ->income()
            ->byMonth($previousMonth->month, $previousMonth->year)
            ->sum('amount');

        $previousExpenses = Transaction::where('user_id', $userId)
            ->expense()
            ->byMonth($previousMonth->month, $previousMonth->year)
            ->sum('amount');

        $this->incomeChange = $previousIncome > 0
            ? round((($this->income - $previousIncome) / $previousIncome) * 100)
            : 0;

        $this->expenseChange = $previousExpenses > 0
            ? round((($this->expenses - $previousExpenses) / $previousExpenses) * 100)
            : 0;

        // Carregar transações recentes
        $this->recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->recent(5)
            ->get();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->loadData();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
