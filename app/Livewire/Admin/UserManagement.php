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
    }

    public function viewUser($userId)
    {
        $this->selectedUser = User::with(['categories', 'transactions'])->findOrFail($userId);
        $this->showUserModal = true;
    }

    public function closeModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
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
