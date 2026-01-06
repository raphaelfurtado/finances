<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-fixed-expenses')->daily();

// Log cada vez que o scheduler roda (ajuda a saber se o cron do cPanel está ativo)
Schedule::call(function () {
    \App\Models\CronLog::create([
        'command' => 'schedule:run',
        'status' => 'success',
        'output' => 'Scheduler executado com sucesso pelo Cron do Sistema.',
        'started_at' => now(),
        'finished_at' => now(),
        'duration_ms' => 0
    ]);
})->everyMinute();
