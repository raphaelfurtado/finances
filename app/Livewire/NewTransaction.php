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

    public function mount($type = 'expense')
    {
        $this->type = $type;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->categories = Category::where('user_id', auth()->id())->get();

        if ($this->categories->isNotEmpty()) {
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
            Transaction::create([
                'user_id' => auth()->id(),
                'category_id' => $this->category_id,
                'type' => $this->type,
                'amount' => (float) $amount,
                'description' => $this->description,
                'date' => $this->date,
                'payment_method' => $this->payment_method,
                'status' => 'paid',
                'is_recurring' => $this->is_recurring,
                'observation' => $this->observation,
            ]);

            session()->flash('message', 'Transação criada com sucesso!');
            return $this->redirect('/', navigate: true);
        } catch (\Exception $e) {
            $this->addError('save', 'Erro ao salvar a transação. Tente novamente.');
        }
    }

    public function render()
    {
        return view('livewire.new-transaction');
    }
}
