<?php

namespace App\Livewire\Admin;

use App\Jobs\SendBroadcastPushJob;
use App\Models\PushCampaign;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PushSender extends Component
{
    use WithPagination;

    public $title = '';
    public $body = '';
    public $target_segment = 'test_user';

    protected $rules = [
        'title' => 'required|max:50',
        'body' => 'required|max:150',
        'target_segment' => 'required|in:all,free,premium,test_user',
    ];

    public function send()
    {
        $this->validate();

        $campaign = PushCampaign::create([
            'title' => $this->title,
            'body' => $this->body,
            'target_segment' => $this->target_segment,
            'created_by' => auth()->id(),
        ]);

        SendBroadcastPushJob::dispatch($campaign);

        $this->reset(['title', 'body', 'target_segment']);

        $message = "Disparo iniciado!";
        if ($campaign->target_segment === 'test_user') {
            $message = "Teste enviado para você!";
        }

        session()->flash('message', $message);
    }

    public function render()
    {
        return view('livewire.admin.push-sender', [
            'history' => PushCampaign::with('creator')
                ->latest()
                ->paginate(10)
        ]);
    }
}
