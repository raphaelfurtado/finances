<?php

namespace App\Livewire;

use Livewire\Component;

class Legal extends Component
{
    public function render()
    {
        return view('livewire.legal')->layout('components.layouts.public');
    }
}
