<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AppSetting;

class AppSettings extends Component
{
    public $maintenance_mode;
    public $maintenance_message;
    public $global_banner_active;
    public $global_banner_message;
    public $global_banner_type;
    public $plan_price;
    public $free_loan_limit;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->maintenance_mode = AppSetting::get('maintenance_mode', 'false') === 'true';
        $this->maintenance_message = AppSetting::get('maintenance_message', 'Estamos em manutenção. Voltaremos em breve!');
        $this->global_banner_active = AppSetting::get('global_banner_active', 'false') === 'true';
        $this->global_banner_message = AppSetting::get('global_banner_message', '');
        $this->global_banner_type = AppSetting::get('global_banner_type', 'info');
        $this->plan_price = AppSetting::getPlanPrice();
        $this->free_loan_limit = AppSetting::getFreeLoanLimit();
    }

    public function toggleMaintenanceMode()
    {
        $this->maintenance_mode = !$this->maintenance_mode;
        AppSetting::set('maintenance_mode', $this->maintenance_mode ? 'true' : 'false');

        session()->flash('message', $this->maintenance_mode
            ? '⚠️ Modo de manutenção ATIVADO'
            : '✅ Modo de manutenção DESATIVADO');
    }

    public function toggleBanner()
    {
        $this->global_banner_active = !$this->global_banner_active;
        AppSetting::set('global_banner_active', $this->global_banner_active ? 'true' : 'false');

        session()->flash('message', $this->global_banner_active
            ? '✅ Banner global ATIVADO'
            : '⚠️ Banner global DESATIVADO');
    }

    public function saveSettings()
    {
        $this->validate([
            'maintenance_message' => 'required|string|max:500',
            'global_banner_message' => 'nullable|string|max:500',
            'global_banner_type' => 'required|in:info,warning,error,success',
            'plan_price' => 'required|string|max:20',
            'free_loan_limit' => 'required|integer|min:1|max:100',
        ]);

        AppSetting::set('maintenance_message', $this->maintenance_message);
        AppSetting::set('global_banner_message', $this->global_banner_message);
        AppSetting::set('global_banner_type', $this->global_banner_type);
        AppSetting::set('plan_price', $this->plan_price);
        AppSetting::set('free_loan_limit', $this->free_loan_limit);

        session()->flash('message', '✅ Configurações salvas com sucesso!');
    }

    public function render()
    {
        return view('livewire.admin.app-settings');
    }
}
