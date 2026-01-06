<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetWarning extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public float $percentage, public string $categoryName)
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
            ->title('Alerta de Orçamento')
            ->icon('/images/icon-192.png')
            ->body("Cuidado, você já usou " . number_format($this->percentage, 0) . "% do orçamento de {$this->categoryName}.")
            ->action('Ver', 'view_budget')
            ->data([
                'id' => $notification->id,
                'url' => route('budgets')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'budget_warning',
            'percentage' => $this->percentage,
            'category_name' => $this->categoryName,
            'title' => 'Alerta de Orçamento',
            'message' => "Cuidado, você já usou " . number_format($this->percentage, 0) . "% do orçamento de {$this->categoryName}.",
        ];
    }
}
