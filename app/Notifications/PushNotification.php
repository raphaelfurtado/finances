<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $url;
    public $icon;

    public function __construct($title, $message, $url = '/', $icon = '/images/icon-192.png')
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->icon = $icon;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon($this->icon)
            ->body($this->message)
            ->action('Ver', 'view_action')
            ->data(['url' => $this->url]);
    }
}
