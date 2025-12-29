<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $premiumUsers;
    public $freeUsers;
    public $monthlyNewUsers;
    public $totalTransactions;
    public $premiumConversionRate;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->premiumUsers = User::where('is_premium', true)->count();
        $this->freeUsers = $this->totalUsers - $this->premiumUsers;

        // Users registered this month
        $this->monthlyNewUsers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $this->totalTransactions = Transaction::count();

        // Premium conversion rate
        $this->premiumConversionRate = $this->totalUsers > 0
            ? round(($this->premiumUsers / $this->totalUsers) * 100, 1)
            : 0;
    }

    public function render()
    {
        $recentUsers = User::latest()->take(10)->get();

        // Monthly user growth (last 6 months)
        $monthlyGrowth = User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('livewire.admin.admin-dashboard', [
            'recentUsers' => $recentUsers,
            'monthlyGrowth' => $monthlyGrowth,
        ]);
    }
}
