<?php

namespace App\Notifications;

use App\Models\FixedExpense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FixedExpenseDue extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public FixedExpense $expense)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', \NotificationChannels\WebPush\WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new \NotificationChannels\WebPush\WebPushMessage)
            ->title('Vencimento Próximo')
            ->icon('/images/icon-192.png')
            ->body("O gasto '{$this->expense->title}' vence em breve (Dia {$this->expense->due_day}).")
            ->action('Ver', 'view_expense')
            ->data([
                'id' => $notification->id,
                'url' => route('fixed-expenses')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'fixed_expense_due',
            'expense_id' => $this->expense->id,
            'title' => 'Gasto Fixo Próximo do Vencimento',
            'message' => "O gasto '{$this->expense->title}' vence em breve (Dia {$this->expense->due_day}).",
        ];
    }
}
