<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Budgets extends Component
{
    public $budgetItems = [];
    public $editingCategoryId = null;
    public $editingAmount = '';
    public $totalBudget = 0;
    public $totalSpent = 0;
    public $totalPercentage = 0;

    public $selectedCategoryId = null;
    public $selectedCategory = null;
    public $categoryTransactions = [];

    protected $listeners = ['budgetUpdated' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $userId = Auth::id();
        $categories = Category::where('user_id', $userId)->with('parent')->orderBy('name')->get();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $this->budgetItems = $categories->map(function ($category) use ($userId, $currentMonthStart, $currentMonthEnd) {
            $budget = Budget::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->first();

            $spent = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                ->sum('amount');

            return [
                'category_id' => $category->id,
                'category' => $category,
                'limit' => $budget ? $budget->amount : 0,
                'spent' => $spent,
                'percentage' => $budget && $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0
            ];
        })->values();

        $this->totalBudget = $this->budgetItems->sum('limit');
        $this->totalSpent = $this->budgetItems->sum('spent');
        $this->totalPercentage = $this->totalBudget > 0 ? ($this->totalSpent / $this->totalBudget) * 100 : 0;

        if ($this->selectedCategoryId) {
            $this->showCategoryDetails($this->selectedCategoryId);
        }
    }

    public function showCategoryDetails($categoryId)
    {
        // Only show details if we are NOT editing
        if ($this->editingCategoryId)
            return;

        $this->selectedCategoryId = $categoryId;

        $item = $this->budgetItems->firstWhere('category_id', $categoryId);
        if ($item) {
            $this->selectedCategory = $item['category'];

            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();

            $this->categoryTransactions = Transaction::where('user_id', Auth::id())
                ->where('category_id', $categoryId)
                ->expense()
                ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
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

    public function editBudget($categoryId, $currentAmount)
    {
        $this->closeCategoryDetails();
        $this->editingCategoryId = $categoryId;
        $this->editingAmount = $currentAmount > 0 ? $currentAmount : '';
    }

    public function cancelEdit()
    {
        $this->editingCategoryId = null;
        $this->editingAmount = '';
    }

    public function saveBudget()
    {
        $this->validate([
            'editingAmount' => 'required|numeric|min:0',
        ]);

        Budget::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'category_id' => $this->editingCategoryId
            ],
            [
                'amount' => $this->editingAmount
            ]
        );

        $this->cancelEdit();
        $this->loadData();
        session()->flash('message', 'Limite atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.budgets');
    }
}
