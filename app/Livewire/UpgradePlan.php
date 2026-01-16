<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;

class UpgradePlan extends Component
{
    public function checkout($priceId)
    {
        if (!$priceId) {
            session()->flash('error', 'Plano inválido.');
            return;
        }

        // Check if it's the free plan
        if ($priceId === 'free') {
            return $this->redirect(route('dashboard'), navigate: true);
        }

        try {
            $checkout = auth()->user()
                ->newSubscription('premium', $priceId)
                ->checkout([
                    'success_url' => route('dashboard', ['checkout' => 'success']),
                    'cancel_url' => route('upgrade'),
                    'client_reference_id' => auth()->id(),
                ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            if (str_contains($e->getMessage(), 'No such customer')) {
                $user = auth()->user();
                $user->stripe_id = null;
                $user->save();

                $checkout = $user->newSubscription('premium', $priceId)
                    ->checkout([
                        'success_url' => route('dashboard', ['checkout' => 'success']),
                        'cancel_url' => route('upgrade'),
                        'client_reference_id' => auth()->id(),
                    ]);
            } else {
                throw $e;
            }
        }

        return $this->redirect($checkout->url, navigate: false);
    }

    public function render()
    {
        $plans = Plan::with('benefits')->where('active', true)->orderBy('price')->get();

        return view('livewire.upgrade-plan', [
            'plans' => $plans
        ]);
    }
}
