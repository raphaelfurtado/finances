<?php

namespace App\Livewire;

use Livewire\Component;

class UpgradePlan extends Component
{
    public function simulatePayment()
    {
        // Simulate successful payment
        auth()->user()->activatePremium(1); // 1 month premium

        session()->flash('message', 'Premium ativado com sucesso! Aproveite os recursos exclusivos.');
        return $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.upgrade-plan');
    }
}
