<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;

class NewTransaction extends Component
{
    public $type = 'expense'; // expense or income
    public $amount = '';
    public $description = '';
    public $category_id = '';
    public $date;
    public $payment_method = 'credit';
    public $is_recurring = false;
    public $observation = '';
    public $categories = [];

    public $transaction_id;
    public $editingTransaction = null;

    public function mount($type = 'expense', $id = null)
    {
        if ($id) {
            $this->editingTransaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
            $this->transaction_id = $id;
            $this->type = $this->editingTransaction->type;
            $this->amount = number_format((float) $this->editingTransaction->amount, 2, ',', '.');
            $this->description = $this->editingTransaction->description;
            $this->category_id = $this->editingTransaction->category_id;
            $this->date = $this->editingTransaction->date->format('Y-m-d');
            $this->payment_method = $this->editingTransaction->payment_method;
            $this->is_recurring = $this->editingTransaction->is_recurring;
            $this->observation = $this->editingTransaction->observation;
        } else {
            $this->type = $type;
            $this->date = Carbon::now()->format('Y-m-d');
        }

        $this->categories = Category::where('user_id', auth()->id())->get();

        if (!$id && $this->categories->isNotEmpty() && !$this->category_id) {
            $this->category_id = $this->categories->first()->id;
        }
    }

    public function save()
    {
        // Validate inputs
        $validated = $this->validate([
            'amount' => 'required',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'payment_method' => 'required|string',
        ], [
            'amount.required' => 'O valor é obrigatório',
            'description.required' => 'A descrição é obrigatória',
            'category_id.required' => 'Selecione uma categoria',
            'date.required' => 'A data é obrigatória',
            'payment_method.required' => 'Selecione um método de pagamento',
        ]);

        // Convert amount from Brazilian format to decimal
        // Remove any non-numeric characters except comma and dot
        $cleanAmount = preg_replace('/[^0-9,.]/', '', $this->amount);

        // If empty or zero, set to 0
        if (empty($cleanAmount) || $cleanAmount === '0' || $cleanAmount === '0,00' || $cleanAmount === '0.00') {
            $this->addError('amount', 'O valor deve ser maior que zero');
            return;
        }

        // Replace comma with dot for decimal conversion
        $amount = str_replace(',', '.', $cleanAmount);

        // Remove any dots that are thousand separators (keep only the last one)
        $parts = explode('.', $amount);
        if (count($parts) > 2) {
            $amount = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
        }

        try {
            if ($this->transaction_id) {
                $transaction = Transaction::where('user_id', auth()->id())->findOrFail($this->transaction_id);

                // Preserve the time if date hasn't changed, or set to start of day if it has
                // Actually, let's just keep the date part since we only have a date input
                // but we'll combine it with the original time if possible
                $newDate = Carbon::parse($this->date)->setTime(
                    $transaction->date->hour,
                    $transaction->date->minute,
                    $transaction->date->second
                );

                $transaction->update([
                    'category_id' => $this->category_id,
                    'type' => $this->type,
                    'amount' => (float) $amount,
                    'description' => $this->description,
                    'date' => $newDate,
                    'payment_method' => $this->payment_method,
                    'is_recurring' => $this->is_recurring,
                    'observation' => $this->observation,
                ]);
                session()->flash('message', 'Transação atualizada com sucesso!');
            } else {
                // For new transactions, use the selected date at CURRENT time
                $transactionDate = Carbon::parse($this->date);
                $now = Carbon::now();

                // If it's today, use exact current time. If not, use start of day but current time is better than 00:00:00 anyway for sorting
                $transactionDate->setTime($now->hour, $now->minute, $now->second);

                Transaction::create([
                    'user_id' => auth()->id(),
                    'category_id' => $this->category_id,
                    'type' => $this->type,
                    'amount' => (float) $amount,
                    'description' => $this->description,
                    'date' => $transactionDate,
                    'payment_method' => $this->payment_method,
                    'status' => 'paid',
                    'is_recurring' => $this->is_recurring,
                    'observation' => $this->observation,
                ]);
                session()->flash('message', 'Transação criada com sucesso!');
            }

            return $this->redirect('/dashboard', navigate: true);
        } catch (\Exception $e) {
            $this->addError('save', 'Erro ao salvar a transação. Tente novamente.');
        }
    }

    public function render()
    {
        return view('livewire.new-transaction');
    }
}
