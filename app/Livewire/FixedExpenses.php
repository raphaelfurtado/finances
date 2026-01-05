<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FixedExpense;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class FixedExpenses extends Component
{
    public $showModal = false;
    public $editingId = null;

    // Payment Modal fields
    public $showPaymentModal = false;
    public $paymentAmount = 0;
    public $paymentTitle = '';
    public $selectedPaymentExpenseId = null;

    // Form fields
    public $title = '';
    public $description = '';
    public $amount = '';
    public $due_day = '';
    public $category_id = '';
    public $is_active = true;
    public $mark_as_paid_now = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'due_day' => 'required|integer|min:1|max:31',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
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
        $this->is_active = true;
        $this->mark_as_paid_now = false;
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
        $this->is_active = $expense->is_active;
        $this->showModal = true;
    }

    public function save()
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
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            FixedExpense::where('user_id', auth()->id())->findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Gasto fixo atualizado com sucesso!');
        } else {
            $expense = FixedExpense::create($data);

            // If user marked as already paid, create the transaction to mark as paid
            if ($this->mark_as_paid_now) {
                $now = Carbon::now();

                // Check if payment already exists for this month (safety)
                $exists = Transaction::where('fixed_expense_id', $expense->id)
                    ->whereMonth('date', $now->month)
                    ->whereYear('date', $now->year)
                    ->exists();

                if (!$exists) {
                    Transaction::create([
                        'user_id' => auth()->id(),
                        'category_id' => $expense->category_id,
                        'type' => 'expense',
                        'amount' => $this->amount,
                        'description' => 'Pgto: ' . $expense->title,
                        'date' => $now,
                        'payment_method' => 'other',
                        'status' => 'paid',
                        'fixed_expense_id' => $expense->id,
                    ]);
                }
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
        $expense = FixedExpense::where('user_id', auth()->id())->findOrFail($id);
        $this->selectedPaymentExpenseId = $expense->id;
        $this->paymentTitle = $expense->title;
        $this->paymentAmount = $expense->amount;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['selectedPaymentExpenseId', 'paymentAmount', 'paymentTitle']);
    }

    public function registerPayment()
    {
        $expense = FixedExpense::where('user_id', auth()->id())->findOrFail($this->selectedPaymentExpenseId);

        $now = Carbon::now();

        // Safety check to avoid double payment
        $exists = Transaction::where('fixed_expense_id', $this->selectedPaymentExpenseId)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Este pagamento já foi registrado este mês.');
            $this->closePaymentModal();
            return;
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $expense->category_id,
            'type' => 'expense',
            'amount' => $this->paymentAmount,
            'description' => 'Pgto: ' . $expense->title,
            'date' => $now,
            'payment_method' => 'other',
            'status' => 'paid',
            'fixed_expense_id' => $expense->id,
        ]);

        session()->flash('message', 'Pagamento registrado com sucesso!');
        $this->closePaymentModal();
        $this->dispatch('transaction-created');
    }

    public function render()
    {
        return view('livewire.fixed-expenses', [
            'fixedExpenses' => FixedExpense::where('user_id', auth()->id())->with('category')->orderBy('due_day')->get(),
            'categories' => Category::where('user_id', auth()->id())->get(),
        ]);
    }
}
