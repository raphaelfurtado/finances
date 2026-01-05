<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;

class Relatorios extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $categoryExpenses = [];
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $chartData = [
        'labels' => [],
        'datasets' => []
    ];

    public $selectedCategoryId = null;
    public $selectedCategory = null;
    public $categoryTransactions = [];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $userId = auth()->id();

        // Get expenses by category
        $expensesByCategory = Transaction::where('user_id', $userId)
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->with('category')
            ->get()
            ->groupBy('category_id');

        $this->categoryExpenses = $expensesByCategory->map(function ($transactions) {
            return [
                'category_id' => $transactions->first()->category_id,
                'category' => $transactions->first()->category,
                'total' => $transactions->sum('amount'),
                'count' => $transactions->count()
            ];
        })->sortByDesc('total')->values();

        // ... rest of loadData ...
        $this->prepareChart();

        $this->totalIncome = Transaction::where('user_id', $userId)
            ->income()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->sum('amount');

        $this->totalExpenses = Transaction::where('user_id', $userId)
            ->expense()
            ->byMonth($this->selectedMonth, $this->selectedYear)
            ->sum('amount');

        if ($this->selectedCategoryId) {
            $this->showCategoryDetails($this->selectedCategoryId);
        }

        $this->dispatch('update-chart', data: $this->chartData);
    }

    private function prepareChart()
    {
        $labels = [];
        $data = [];
        $colors = [];

        foreach ($this->categoryExpenses as $item) {
            $labels[] = $item['category']->name ?? 'Sem Categoria';
            $data[] = $item['total'];
            $colors[] = $item['category']->color ?? '#9CA3AF';
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0
                ]
            ]
        ];
    }

    public function showCategoryDetails($categoryId)
    {
        $this->selectedCategoryId = $categoryId;

        $item = $this->categoryExpenses->firstWhere('category_id', $categoryId);
        if ($item) {
            $this->selectedCategory = $item['category'];
            $this->categoryTransactions = Transaction::where('user_id', auth()->id())
                ->where('category_id', $categoryId)
                ->expense()
                ->byMonth($this->selectedMonth, $this->selectedYear)
                ->orderBy('date', 'desc')
                ->get();
        }
    }

    public function closeCategoryDetails()
    {
        $this->selectedCategoryId = null;
        $this->selectedCategory = null;
        $this->categoryTransactions = [];
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->closeCategoryDetails();
        $this->loadData();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->closeCategoryDetails();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.relatorios');
    }
}
