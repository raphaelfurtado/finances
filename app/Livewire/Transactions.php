<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Transactions extends Component
{
    use WithPagination;

    public function render()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('category.parent')
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('livewire.transactions', [
            'transactions' => $transactions
        ]);
    }
}
