<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Transactions extends Component
{
    use WithPagination;

    public $currentMonth;
    public $currentYear;

    // Payment Modal fields
    public $showPaymentModal = false;
    public $paymentAmount = 0;
    public $paymentTitle = '';
    public $paymentIcon = '';
    public $paymentColor = '';
    public $selectedFixedExpenseId = null;

    protected $service;

    public function boot(\App\Services\TransactionService $service)
    {
        $this->service = $service;
    }

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
    }

    public function prevMonth()
    {
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
    }

    public function openPaymentModal($fixedExpenseId)
    {
        $expense = \App\Models\FixedExpense::where('user_id', auth()->id())
            ->with('category')
            ->findOrFail($fixedExpenseId);

        $this->selectedFixedExpenseId = $expense->id;
        $this->paymentTitle = $expense->title;
        $this->paymentAmount = $expense->amount;
        $this->paymentIcon = $expense->category->icon ?? 'credit-card';
        $this->paymentColor = $expense->category->color ?? '#cbd5e1';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['selectedFixedExpenseId', 'paymentAmount', 'paymentTitle']);
    }

    public function registerPayment()
    {
        $expense = \App\Models\FixedExpense::where('user_id', auth()->id())->findOrFail($this->selectedFixedExpenseId);

        // Date for the transaction (using due day of the projected month)
        $date = \Carbon\Carbon::createFromDate($this->currentYear, $this->currentMonth, $expense->due_day);

        // Safety check
        $exists = Transaction::where('fixed_expense_id', $this->selectedFixedExpenseId)
            ->whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Este pagamento já foi registrado.');
            $this->closePaymentModal();
            return;
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $expense->category_id,
            'type' => $expense->type,
            'amount' => $this->paymentAmount,
            'description' => 'Pgto: ' . $expense->title,
            'date' => $date,
            'payment_method' => 'other',
            'status' => 'paid',
            'is_recurring' => true,
            'fixed_expense_id' => $expense->id,
        ]);

        session()->flash('message', 'Pagamento confirmado!');
        $this->closePaymentModal();
        $this->dispatch('transaction-created');
    }

    public function deleteTransaction($id)
    {
        Transaction::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->dispatch('transaction-created'); // Refresh data
    }

    public function render()
    {
        // Use Service to get merged list
        $transactions = $this->service->getTransactionsForMonth(
            auth()->id(),
            $this->currentMonth,
            $this->currentYear
        );

        // Calculate Totals (on the full collection)
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Calculate Projected (Forecast) portions
        $projectedIncome = $transactions->where('type', 'income')->where('is_projected', true)->sum('amount');
        $projectedExpense = $transactions->where('type', 'expense')->where('is_projected', true)->sum('amount');

        // Pagination (Manual pagination for collection)
        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $transactions->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.transactions', [
            'transactions' => $paginatedItems,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'projectedIncome' => $projectedIncome,
            'projectedExpense' => $projectedExpense,
        ]);
    }
}
