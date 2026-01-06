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

        if (!$user->isPremium()) {
            session()->flash('error', 'Você não possui uma assinatura ativa.');
            return;
        }

        try {
            // 1. Try Cashier method
            if ($user->subscribed('premium')) {
                $user->subscription('premium')->cancelAtPeriodEnd();
            }
            // 2. Try direct Stripe API using stored ID
            elseif ($user->stripe_subscription_id) {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $stripe->subscriptions->update($user->stripe_subscription_id, [
                    'cancel_at_period_end' => true,
                ]);
            } else {
                throw new \Exception('Assinatura não encontrada para cancelamento.');
            }

            session()->flash('message', 'Assinatura cancelada com sucesso. Acesso mantido até o fim do período.');
            $this->showCancelModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao cancelar: ' . $e->getMessage());
        }
    }

    public function resumeSubscription()
    {
        $user = auth()->user();
        // Similar fallback logic could be applied here if needed
        if ($user->subscribed('premium')) {
            $user->subscription('premium')->resume();
            session()->flash('message', 'Assinatura reativada!');
        } elseif ($user->stripe_subscription_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $stripe->subscriptions->update($user->stripe_subscription_id, [
                    'cancel_at_period_end' => false,
                ]);
                session()->flash('message', 'Assinatura reativada!');
            } catch (\Exception $e) {
                session()->flash('error', 'Erro: ' . $e->getMessage());
            }
        }
    }

    public function render()
    {
        $user = auth()->user();
        $subscription = $user->subscription('premium');
        $onGracePeriod = false;

        // Check grace period manually if no Cashier subscription
        if ($subscription) {
            $onGracePeriod = $subscription->onGracePeriod();
        } elseif ($user->stripe_subscription_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $stripeSub = $stripe->subscriptions->retrieve($user->stripe_subscription_id);
                $onGracePeriod = $stripeSub->cancel_at_period_end;

                // Hack: Pass stripe object as subscription for view compatibility
                if (!$subscription) {
                    $subscription = $stripeSub;
                    // Decorate with ends_at for view
                    $subscription->ends_at = isset($stripeSub->current_period_end)
                        ? \Carbon\Carbon::createFromTimestamp($stripeSub->current_period_end)
                        : null;

                    // Helper method for view compatibility
                    $subscription->asStripeSubscription = function () use ($stripeSub) {
                        return $stripeSub;
                    };
                }
            } catch (\Exception $e) {
                // Ignore API errors, assume active
            }
        }

        return view('livewire.manage-subscription', [
            'subscription' => $subscription,
            // If no subscription object, we assume active unless verified otherwise, 
            // strictly for the UI display logic we might need to adjust.
            'onGracePeriod' => $onGracePeriod,
        ]);
    }
}
