<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $title, public string $body)
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
            ->title($this->title)
            ->icon('/images/icon-192.png')
            ->body($this->body)
            ->action('Ver', 'view_notification')
            ->data([
                'id' => $notification->id,
                'url' => route('dashboard')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'admin_message',
            'title' => $this->title,
            'message' => $this->body,
        ];
    }
}
