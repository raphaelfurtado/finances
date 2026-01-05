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
            FixedExpense::create($data);
            session()->flash('message', 'Gasto fixo criado com sucesso!');
        }

        $this->closeModal();
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
