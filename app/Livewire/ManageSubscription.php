<?php

namespace App\Livewire;

use Livewire\Component;
use Laravel\Cashier\Cashier;

class ManageSubscription extends Component
{
    public $showCancelModal = false;

    public function openCancelModal()
    {
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    public function cancelSubscription()
    {
        $user = auth()->user();

        if (!$user->subscribed('premium')) {
            session()->flash('error', 'Você não possui uma assinatura ativa.');
            return;
        }

        try {
            // Cancel subscription at period end (customer keeps access until end of billing period)
            $user->subscription('premium')->cancelAtPeriodEnd();

            session()->flash('message', 'Assinatura cancelada com sucesso. Você manterá acesso Premium até o fim do período atual.');
            $this->showCancelModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao cancelar assinatura: ' . $e->getMessage());
        }
    }

    public function resumeSubscription()
    {
        $user = auth()->user();

        if (!$user->subscribed('premium')) {
            session()->flash('error', 'Você não possui uma assinatura ativa.');
            return;
        }

        try {
            $user->subscription('premium')->resume();
            session()->flash('message', 'Assinatura reativada com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao reativar assinatura: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $user = auth()->user();
        $subscription = $user->subscription('premium');

        return view('livewire.manage-subscription', [
            'subscription' => $subscription,
            'onGracePeriod' => $subscription ? $subscription->onGracePeriod() : false,
        ]);
    }
}
