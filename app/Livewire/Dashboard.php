<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Transaction;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $selectedMonth;
    public $selectedYear;

    protected $listeners = ['transaction-created' => '$refresh'];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;

        // Sync subscription if returning from Stripe Checkout success
        if (request()->query('checkout') === 'success') {
            try {
                auth()->user()->updateSubscriptions();
            } catch (\Throwable $e) {
                // Ignore; fall back to standard display
            }
        }
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    #[Computed]
    public function monthlyTotals()
    {
        $row = Transaction::where('user_id', auth()->id())
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income")
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense")
            ->first();

        return [
            'income' => (float) $row->income,
            'expense' => (float) $row->expense,
        ];
    }

    #[Computed]
    public function monthlyIncome()
    {
        return $this->monthlyTotals['income'];
    }

    #[Computed]
    public function monthlyExpense()
    {
        return $this->monthlyTotals['expense'];
    }

    #[Computed]
    public function monthlyBalance()
    {
        return $this->monthlyIncome - $this->monthlyExpense;
    }

    #[Computed]
    public function totalExpenses()
    {
        // Used as denominator for the spending bars; avoid division by zero.
        return $this->monthlyExpense > 0 ? $this->monthlyExpense : 1;
    }

    #[Computed]
    public function globalBalance()
    {
        $totals = Transaction::where('user_id', auth()->id())
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income")
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense")
            ->first();

        return (float) $totals->total_income - (float) $totals->total_expense;
    }

    #[Computed]
    public function categoryRanking()
    {
        return Transaction::where('user_id', auth()->id())
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->selectRaw('category_id, sum(amount) as total_amount')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->with('category')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentTransactions()
    {
        return Transaction::where('user_id', auth()->id())
            ->with('category.parent')
            ->recent(5)
            ->get();
    }

    #[Computed]
    public function advice()
    {
        $income = $this->monthlyIncome;
        $expense = $this->monthlyExpense;
        $top = $this->categoryRanking->first();

        // Gastou mais do que ganhou
        if ($expense > $income && $income > 0) {
            return $this->pick([
                "Parabéns! Você está oficialmente patrocinando a riqueza alheia enquanto empobrece.",
                "Se continuar assim, o Serasa vai te mandar cartão de natal.",
                "A conta não fecha, e a culpa não é da matemática.",
            ]);
        }

        // Concentrou o gasto em uma categoria
        if ($top && $top->category && $expense > 0) {
            $percentage = ($top->total_amount / $expense) * 100;
            if ($percentage > 40) {
                $name = $top->category->name;
                return $this->pick([
                    "Você trabalha só para pagar {$name}? É o que parece.",
                    "Se {$name} fosse uma pessoa, vocês já estariam casados.",
                    "Investir em {$name} não aposenta ninguém, viu?",
                ]);
            }
        }

        // Sobrou dinheiro
        if (($income - $expense) > 0) {
            return $this->pick([
                "Sobrou dinheiro? Certeza que você não esqueceu de pagar nada?",
                "Olha só, alguém está se sentindo o Lobo de Wall Street.",
                "Não gaste tudo em Tigrinho, hein?",
                "Milagre! Tá sobrando dinheiro..",
            ]);
        }

        return "Sua vida financeira está tão parada que nem eu tenho o que falar.";
    }

    private function pick(array $messages): string
    {
        return $messages[array_rand($messages)];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}