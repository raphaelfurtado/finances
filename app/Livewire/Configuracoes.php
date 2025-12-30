<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Models\Transaction;

class Configuracoes extends Component
{
    public $notifications_enabled;
    public $dark_mode;
    public $currency;
    public $start_of_month;

    public function mount()
    {
        $user = auth()->user();
        $this->notifications_enabled = $user->notifications_enabled;
        $this->dark_mode = $user->dark_mode;
        $this->currency = $user->currency;
        $this->start_of_month = $user->start_of_month;
    }

    public function updated($propertyName)
    {
        $user = auth()->user();
        $user->forceFill([
            $propertyName => $this->$propertyName,
        ])->save();

        $this->dispatch('settings-updated');
    }

    public function exportData()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=transacoes.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Data', 'Descrição', 'Categoria', 'Tipo', 'Valor', 'Método', 'Status', 'Recorrente', 'Observação']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->date->format('Y-m-d'),
                    $transaction->description,
                    $transaction->category->name ?? 'Sem categoria',
                    $transaction->type,
                    $transaction->amount,
                    $transaction->payment_method,
                    $transaction->status,
                    $transaction->is_recurring ? 'Sim' : 'Não',
                    $transaction->observation
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deleteAccount()
    {
        // Implement delete logic later or now if simple
        // For now just redirecting or showing a message could be enough
        // But user asked to implement settings features. Delete account wasn't explicitly asked but it's in the UI.
        // Focusing on requested features first.
    }

    public function render()
    {
        return view('livewire.configuracoes');
    }
}
