<?php

namespace App\Livewire;

use Livewire\Component;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;

class NotificationSettings extends Component
{
    public $loading = false;

    public function sendTestNotification()
    {
        $user = auth()->user();

        if ($user->pushSubscriptions()->count() === 0) {
            session()->flash('error', 'Você ainda não ativou as notificações neste navegador.');
            return;
        }

        try {
            $user->notify(new PushNotification(
                'Teste de Notificação',
                'Isso é um teste do sistema de finanças! Se você recebeu isso, as chaves VAPID estão funcionando.',
                route('dashboard')
            ));

            session()->flash('message', 'Notificação de teste enviada!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao enviar: ' . $e->getMessage());
        }
    }

    public function clearSubscriptions()
    {
        auth()->user()->pushSubscriptions()->delete();
        session()->flash('message', 'Todas as inscrições de push foram removidas.');
    }

    public function updateSubscription($subscription)
    {
        auth()->user()->updatePushSubscription(
            $subscription['endpoint'],
            $subscription['publicKey'] ?? null,
            $subscription['authToken'] ?? null,
            $subscription['contentEncoding'] ?? 'aesgcm'
        );

        session()->flash('message', 'Notificações ativadas neste navegador!');
    }

    public function render()
    {
        return view('livewire.notification-settings', [
            'subscriptionCount' => auth()->user()->pushSubscriptions()->count()
        ]);
    }
}
