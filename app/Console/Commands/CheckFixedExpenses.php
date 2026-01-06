<?php

namespace App\Console\Commands;

use App\Models\FixedExpense;
use App\Models\User;
use App\Notifications\FixedExpenseDue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckFixedExpenses extends Command
{
    protected $signature = 'app:check-fixed-expenses';
    protected $description = 'Verifica gastos fixos que vencem em 2 dias e notifica o usuário se não estiverem pagos.';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2);
        $dueDay = $targetDate->day;

        $expenses = FixedExpense::where('due_day', $dueDay)
            ->where('is_active', true)
            ->with(['user', 'transactions'])
            ->get();

        $count = 0;

        foreach ($expenses as $expense) {
            $alreadyPaid = $expense->transactions()
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->exists();

            if (!$alreadyPaid) {
                $expense->user->notify(new FixedExpenseDue($expense));
                $count++;
            }
        }

        $this->info("Verificação concluída. {$count} notificações enviadas.");
    }
}
