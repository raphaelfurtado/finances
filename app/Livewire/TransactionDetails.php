<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;

class TransactionDetails extends Component
{
    public $transaction;

    public function mount($id)
    {
        $this->transaction = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }

    public function delete()
    {
        $this->transaction->delete();
        return redirect('/')->with('message', 'Transação excluída com sucesso!');
    }

    public function render()
    {
        return view('livewire.transaction-details');
    }
}
