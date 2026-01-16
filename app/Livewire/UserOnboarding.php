<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;

#[Layout('components.layouts.app')]
class UserOnboarding extends Component
{
    public $step = 1;

    // Data
    public $employment_status;
    public $family_structure;
    public $financial_goal;
    public $birth_date;

    public function mount()
    {
        // If user already has data, redirect or just let them edit?
        // For now, let's assume they can re-calibrate.
        $user = auth()->user();
        $this->employment_status = $user->employment_status;
        $this->family_structure = $user->family_structure;
        $this->financial_goal = $user->financial_goal;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
    }

    public function setOption($field, $value)
    {
        $this->$field = $value;
        $this->nextStep();
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function save()
    {
        $this->validate([
            'birth_date' => 'nullable|date',
            'employment_status' => 'nullable|string',
            'family_structure' => 'nullable|string',
            'financial_goal' => 'nullable|string',
        ]);

        auth()->user()->update([
            'employment_status' => $this->employment_status,
            'family_structure' => $this->family_structure,
            'financial_goal' => $this->financial_goal,
            'birth_date' => $this->birth_date,
        ]);

        session()->flash('message', 'Perfil atualizado! Agora minhas dicas serão mais certeiras (e dolorosas).');
        return $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.user-onboarding');
    }
}
