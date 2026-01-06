<?php

namespace App\Livewire\Admin;

use App\Models\CronLog;
use Livewire\Component;
use Livewire\WithPagination;

class CronMonitoring extends Component
{
    use WithPagination;

    public function mount()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function clearLogs()
    {
        CronLog::truncate();
        session()->flash('message', 'Todos os logs foram apagados.');
    }

    public function render()
    {
        $logs = CronLog::orderBy('started_at', 'desc')->paginate(15);

        return view('livewire.admin.cron-monitoring', [
            'logs' => $logs
        ]);
    }
}
