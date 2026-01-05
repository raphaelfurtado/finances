<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBanner extends Component
{
    public $show = false;

    public function mount()
    {
        // We'll determine visibility on the frontend to avoid layout shift
    }

    public function updateSubscription($subscription)
    {
        auth()->user()->updatePushSubscription(
            $subscription['endpoint'],
            $subscription['publicKey'] ?? null,
            $subscription['authToken'] ?? null,
            $subscription['contentEncoding'] ?? 'aesgcm'
        );

        $this->show = false;
        session()->flash('message', 'Notificações ativadas com sucesso!');
    }

    public function render()
    {
        return view('livewire.notification-banner');
    }
}
