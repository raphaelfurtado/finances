<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPremium = 'all'; // all, premium, free
    public $selectedUser = null;
    public $showUserModal = false;

    // Local onboarding properties for better binding
    public $employment_status = '';
    public $family_structure = '';
    public $financial_goal = '';
    public $birth_date = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPremium()
    {
        $this->resetPage();
    }

    public function togglePremium($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->isPremium()) {
            $user->deactivatePremium();
            session()->flash('message', "Premium desativado para {$user->name}");
        } else {
            $user->activatePremium(1); // 1 month
            session()->flash('message', "Premium ativado para {$user->name} por 1 mês");
        }

        $this->dispatch('userUpdated');

        if ($this->selectedUser && $this->selectedUser->id === $userId) {
            $this->selectedUser->refresh();
        }
    }

    public function resetWizard($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'birth_date' => null,
            'employment_status' => null,
            'family_structure' => null,
            'financial_goal' => null,
        ]);

        session()->flash('message', "Wizard reiniciado para {$user->name}");

        if ($this->selectedUser && $this->selectedUser->id === $userId) {
            $this->selectedUser->refresh();
            $this->employment_status = '';
            $this->family_structure = '';
            $this->financial_goal = '';
            $this->birth_date = '';
        }
    }

    public function saveOnboarding()
    {
        if (!$this->selectedUser)
            return;

        $this->selectedUser->update([
            'employment_status' => $this->employment_status,
            'family_structure' => $this->family_structure,
            'financial_goal' => $this->financial_goal,
            'birth_date' => $this->birth_date ?: null,
        ]);

        session()->flash('message', "Perfil financeiro de {$this->selectedUser->name} atualizado.");
    }

    public function viewUser($userId)
    {
        $this->selectedUser = User::with(['categories', 'transactions'])->findOrFail($userId);

        // Populate local properties
        $this->employment_status = $this->selectedUser->employment_status ?? '';
        $this->family_structure = $this->selectedUser->family_structure ?? '';
        $this->financial_goal = $this->selectedUser->financial_goal ?? '';
        $this->birth_date = $this->selectedUser->birth_date?->format('Y-m-d') ?? '';

        $this->showUserModal = true;
    }

    public function closeModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    public function impersonate($userId)
    {
        if (auth()->id() === $userId) {
            return;
        }

        // Store the original admin user ID in session
        session()->put('impersonator_id', auth()->id());

        // Login as the selected user
        auth()->loginUsingId($userId);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $query = User::query();

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Premium filter
        if ($this->filterPremium === 'premium') {
            $query->where('is_premium', true);
        } elseif ($this->filterPremium === 'free') {
            $query->where('is_premium', false);
        }

        $users = $query->latest()->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
