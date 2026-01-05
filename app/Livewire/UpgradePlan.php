<?php

namespace App\Livewire;

use Livewire\Component;

class UpgradePlan extends Component
{
    public function checkout()
    {
        $priceId = config('services.stripe.premium_price_id');

        if (!$priceId) {
            session()->flash('error', 'Configuração do Stripe incompleta (ID do preço ausente).');
            return;
        }

        $checkout = auth()->user()
            ->newSubscription('premium', $priceId)
            ->checkout([
                'success_url' => route('dashboard', ['checkout' => 'success']),
                'cancel_url' => route('upgrade'),
                'client_reference_id' => auth()->id(),
            ]);

        return $this->redirect($checkout->url, navigate: false);
    }

    public function render()
    {
        return view('livewire.upgrade-plan');
    }
}
