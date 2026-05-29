<?php

namespace App\Console\Commands;

use App\Models\FixedExpense;
use App\Notifications\FixedExpenseDue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckFixedExpenses extends Command
{
    protected $signature = 'app:check-fixed-expenses';
    protected $description = 'Verifica gastos fixos que vencem em 2 dias e notifica o usuário se não estiverem pagos.';

    public function handle()
    {
        $startTime = now();
        $log = \App\Models\CronLog::create([
            'command' => 'app:check-fixed-expenses',
            'status' => 'running',
            'started_at' => $startTime
        ]);

        try {
            $today = Carbon::now()->startOfDay();

            // Load active expenses that have started, then match on the clamped due
            // date so bills due on the 29th-31st still fire in short months.
            $expenses = FixedExpense::where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', Carbon::now()->endOfMonth());
                })
                ->with(['user', 'transactions'])
                ->get();

            $count = 0;

            foreach ($expenses as $expense) {
                // Notify exactly 2 days before the (clamped) due date this month.
                if ($today->diffInDays($expense->dueDateFor($today), false) !== 2) {
                    continue;
                }

                $alreadyPaid = $expense->transactions()
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->exists();

                if (!$alreadyPaid) {
                    $expense->user->notify(new FixedExpenseDue($expense));
                    $count++;
                }
            }

            $message = "Verificação concluída. {$count} notificações enviadas.";
            $this->info($message);

            $log->update([
                'status' => 'success',
                'output' => $message,
                'finished_at' => now(),
                'duration_ms' => now()->diffInMilliseconds($startTime)
            ]);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $log->update([
                'status' => 'failed',
                'output' => $e->getMessage(),
                'finished_at' => now(),
                'duration_ms' => now()->diffInMilliseconds($startTime)
            ]);
        }
    }
}
