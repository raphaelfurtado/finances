<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\FixedExpense;
use App\Models\Category;
use App\Services\TransactionService;
use Carbon\Carbon;

class FixedExpenses extends Component
{
    public $showModal = false;
    public $editingId = null;

    // Payment Modal fields
    public $showPaymentModal = false;
    public $paymentAmount = 0;
    public $paymentTitle = '';
    public $paymentIcon = '';
    public $paymentColor = '';
    public $selectedPaymentExpenseId = null;

    public $activeFilter = 'all';

    // Form fields
    public $title = '';
    public $description = '';
    public $amount = '';
    public $due_day = '';
    public $category_id = '';
    public $type = 'expense';
    public $is_active = true;
    public $mark_as_paid_now = false;
    public $start_month = 'current';

    /** Status ordering so action items (overdue/urgent) float to the top. */
    private const STATUS_PRIORITY = [
        'overdue' => 0,
        'due_soon' => 1,
        'pending' => 2,
        'not_started' => 3,
        'paid' => 4,
    ];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'due_day' => 'required|integer|min:1|max:31',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:expense,income',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Single source of truth for the summary cards and the month progress bar.
     * One query (active expenses + this month's transactions eager-loaded).
     */
    #[Computed]
    public function overview()
    {
        $now = Carbon::now();

        $active = FixedExpense::where('user_id', auth()->id())
            ->where('is_active', true)
            ->with(['transactions' => fn($q) => $q->whereMonth('date', $now->month)->whereYear('date', $now->year)])
            ->get();

        $expenses = $active->where('type', 'expense');
        $incomes = $active->where('type', 'income');

        $expenseTotal = (float) $expenses->sum('amount');
        $incomeTotal = (float) $incomes->sum('amount');

        $paidItems = $expenses->filter(fn($e) => $e->status_this_month === 'paid');
        $paid = (float) $paidItems->sum('amount');

        return [
            'expenseTotal' => $expenseTotal,
            'expenseCount' => $expenses->count(),
            'incomeTotal' => $incomeTotal,
            'incomeCount' => $incomes->count(),
            'recurringLeftover' => $incomeTotal - $expenseTotal,
            'paid' => $paid,
            'remaining' => max(0, $expenseTotal - $paid),
            'paidCount' => $paidItems->count(),
            'pendingCount' => $expenses->count() - $paidItems->count(),
            'percent' => $expenseTotal > 0 ? min(100, (int) round(($paid / $expenseTotal) * 100)) : 0,
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->amount = '';
        $this->due_day = '';
        $this->category_id = '';
        $this->type = 'expense';
        $this->is_active = true;
        $this->mark_as_paid_now = false;
        $this->start_month = 'current';
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $expense = FixedExpense::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $expense->id;
        $this->title = $expense->title;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->due_day = $expense->due_day;
        $this->category_id = $expense->category_id;
        $this->type = $expense->type;
        $this->is_active = $expense->is_active;
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function save(TransactionService $transactions)
    {
        // Freemium check: limit to 1 fixed expense for free users
        if (!$this->editingId && !auth()->user()->is_premium) {
            $currentCount = FixedExpense::where('user_id', auth()->id())->count();
            if ($currentCount >= 1) {
                session()->flash('error', 'Usuários gratuitos podem cadastrar apenas 1 gasto fixo. Faça upgrade para Premium para adicionar mais!');
                return;
            }
        }

        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'due_day' => $this->due_day,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'start_date' => $this->start_month === 'next' ? Carbon::now()->addMonth()->startOfMonth() : Carbon::now()->startOfMonth(),
        ];

        if ($this->editingId) {
            FixedExpense::where('user_id', auth()->id())->findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Gasto fixo atualizado com sucesso!');
        } else {
            $expense = FixedExpense::create($data);

            if ($this->mark_as_paid_now) {
                $transactions->payFixedExpense($expense, (float) $this->amount);
            }

            session()->flash('message', 'Gasto fixo criado com sucesso!');
        }

        $this->closeModal();
        $this->dispatch('transaction-created');
    }

    public function delete($id)
    {
        FixedExpense::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Gasto fixo removido com sucesso!');
    }

    public function toggleStatus($id)
    {
        $expense = FixedExpense::where('user_id', auth()->id())->findOrFail($id);
        $expense->update(['is_active' => !$expense->is_active]);
    }

    public function openPaymentModal($id)
    {
        $expense = FixedExpense::where('user_id', auth()->id())->with('category')->findOrFail($id);
        $this->selectedPaymentExpenseId = $expense->id;
        $this->paymentTitle = $expense->title;
        $this->paymentAmount = $expense->amount;
        $this->paymentIcon = $expense->category->icon ?? 'credit-card';
        $this->paymentColor = $expense->category->color ?? '#cbd5e1';
        $this->showPaymentModal = true;
        $this->dispatch('modal-opened');
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['selectedPaymentExpenseId', 'paymentAmount', 'paymentTitle']);
    }

    public function registerPayment(TransactionService $transactions)
    {
        $expense = FixedExpense::where('user_id', auth()->id())->findOrFail($this->selectedPaymentExpenseId);

        $payment = $transactions->payFixedExpense($expense, (float) $this->paymentAmount);

        if (!$payment) {
            session()->flash('error', 'Este pagamento já foi registrado este mês.');
            $this->closePaymentModal();
            return;
        }

        session()->flash('message', 'Pagamento registrado com sucesso!');
        $this->closePaymentModal();
        $this->dispatch('transaction-created');
    }

    public function render()
    {
        $now = Carbon::now();

        $query = FixedExpense::where('user_id', auth()->id())
            ->with('category')
            ->with(['transactions' => fn($q) => $q->whereMonth('date', $now->month)->whereYear('date', $now->year)]);

        if ($this->activeFilter !== 'all') {
            $query->where('type', $this->activeFilter);
        }

        $fixedExpenses = $query->orderBy('due_day')->get()
            ->sortBy(fn($e) => self::STATUS_PRIORITY[$e->status_this_month] ?? 9)
            ->values();

        return view('livewire.fixed-expenses', [
            'fixedExpenses' => $fixedExpenses,
            'categories' => Category::where('user_id', auth()->id())->get(),
        ]);
    }
}