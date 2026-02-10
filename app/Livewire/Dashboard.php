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
    public $income = 0;
    public $expenses = 0;
    public $balance = 0;
    public $incomeChange = 0;
    public $expenseChange = 0;
    public $recentTransactions = [];

    protected $listeners = ['transaction-created' => 'loadData'];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;

        // Sync subscription if return from Stripe Checkout success
        if (request()->query('checkout') === 'success') {
            try {
                auth()->user()->updateSubscriptions();
            } catch (\Exception $e) {
                // Ignore if it fails, fallback to standard display
            }
        }

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
            ->with('category.parent')
            ->recent(5)
            ->get();

        $this->generateAdvice($userId);
    }

    public $advice = '';

    public function generateAdvice($userId)
    {
        $topExpense = Transaction::where('user_id', $userId)
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category.parent')
            ->first();

        // Cenário 1: Gastou mais do que ganhou
        if ($this->expenses > $this->income && $this->income > 0) {
            $messages = [
                "Parabéns! Você está oficialmente patrocinando a riqueza alheia enquanto empobrece.",
                "Se continuar assim, o Serasa vai te mandar cartão de natal.",
                "A conta não fecha, e a culpa não é da matemática.",
            ];
            $this->advice = $messages[array_rand($messages)];
            return;
        }

        // Cenário 2: Gastou muito em uma categoria específica
        if ($topExpense && $this->expenses > 0) {
            $percentage = ($topExpense->total / $this->expenses) * 100;
            if ($percentage > 40) {
                $categoryName = $topExpense->category->name ?? 'coisas aleatórias';
                $messages = [
                    "Você trabalha só para pagar {$categoryName}? É o que parece.",
                    "Se {$categoryName} fosse uma pessoa, vocês já estariam casados.",
                    "Investir em {$categoryName} não aposenta ninguém, viu?",
                ];
                $this->advice = $messages[array_rand($messages)];
                return;
            }
        }

        // Cenário 3: Sobrou dinheiro (Milagre)
        if ($this->balance > 0) {
            $messages = [
                "Sobrou dinheiro? Certeza que você não esqueceu de pagar nada?",
                "Olha só, alguém está se sentindo o Lobo de Wall Street.",
                "Não gaste tudo em negócio de Tigrinho, hein?",
                "Milagre! Tá sobrando dinheiro.."
            ];
            $this->advice = $messages[array_rand($messages)];
            return;
        }

        // Default
        $this->advice = "Sua vida financeira está tão parada que nem eu tenho o que falar.";
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
    public function totalExpenses()
    {
        $total = Transaction::where('user_id', auth()->id())
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->sum('amount');

        return $total > 0 ? $total : 1;
    }

    #[Computed]
    public function consultorComment()
    {
        $topCategory = $this->categoryRanking->first();

        if (!$topCategory || !$topCategory->category) {
            return "Sua vida financeira está tão parada que nem eu tenho o que falar.";
        }

        $categoryName = strtolower($topCategory->category->name);

        if (str_contains($categoryName, 'ifood') || str_contains($categoryName, 'alimentação')) {
            return "Patrocinador oficial do Delivery.";
        }

        if (str_contains($categoryName, 'uber') || str_contains($categoryName, 'transporte')) {
            return "Já pensou em andar a pé? É de graça.";
        }

        if (str_contains($categoryName, 'lazer') || str_contains($categoryName, 'bar')) {
            return "Seu fígado pede socorro tanto quanto sua carteira.";
        }

        return "Seu maior gasto é com " . ($topCategory->category->name ?? 'coisas aleatórias') . ". Interessante...";
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
