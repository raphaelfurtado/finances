<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\AdminMessage;
use Livewire\Component;

class SendNotification extends Component
{
    public $title = '';
    public $message = '';
    public $sending = false;

    public function mount()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    protected $rules = [
        'title' => 'required|min:3|max:50',
        'message' => 'required|min:10|max:500',
    ];

    public function send()
    {
        $this->validate();
        $this->sending = true;

        $users = User::all();
        $delay = 0;

        foreach ($users as $user) {
            // Envia para a fila com um pequeno delay progressivo para não sobrecarregar
            $user->notify((new AdminMessage($this->title, $this->message))->delay(now()->addSeconds($delay)));

            // Incrementa 1 segundo a cada 10 usuários para distribuir a carga
            if ($users->count() > 50) {
                $delay += 0.1;
            }
        }

        $this->reset(['title', 'message']);
        $this->sending = false;

        session()->flash('message', 'Notificações enviadas para a fila com sucesso!');
    }

    public function render()
    {
        return view('livewire.admin.send-notification');
    }
}
