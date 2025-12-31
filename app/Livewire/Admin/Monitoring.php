<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Monitoring extends Component
{
    public $days = 7;

    public function render()
    {
        $startDate = now()->subDays($this->days);

        // Top Feature Areas
        $topFeatures = ActivityLog::where('created_at', '>=', $startDate)
            ->select('feature_area', DB::raw('count(*) as total'))
            ->groupBy('feature_area')
            ->orderByDesc('total')
            ->get();

        // Top Pages
        $topPages = ActivityLog::where('created_at', '>=', $startDate)
            ->select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Active Users (last 24h)
        $activeUsers = ActivityLog::where('created_at', '>=', now()->subDay())
            ->distinct('user_id')
            ->count('user_id');

        // Total interactions
        $totalInteractions = ActivityLog::where('created_at', '>=', $startDate)->count();

        // Interactions by Day for simple chart
        $dailyInteractions = ActivityLog::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent Users
        $recentUsers = \App\Models\User::latest()->take(5)->get();

        // Critical Errors (last 24h)
        $criticalErrors = \App\Models\SystemError::latest()
            ->where('created_at', '>=', now()->subDay())
            ->take(5)
            ->get();

        return view('livewire.admin.monitoring', [
            'topFeatures' => $topFeatures,
            'topPages' => $topPages,
            'activeUsers' => $activeUsers,
            'totalInteractions' => $totalInteractions,
            'dailyInteractions' => $dailyInteractions,
            'recentUsers' => $recentUsers,
            'criticalErrors' => $criticalErrors,
        ])->layout('components.layouts.app');
    }
}
