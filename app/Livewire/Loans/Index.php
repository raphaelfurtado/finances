<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user = auth()->user();
        $loans = Loan::where('user_id', $user->id)
            ->with('installments')
            ->latest()
            ->get();

        $totalLoansCount = $loans->count();
        $loanLimit = \App\Models\AppSetting::getFreeLoanLimit();
        $isPremium = $user->isPremium();

        $totalPayable = $loans->where('type', 'payable')
            ->where('status', 'active')
            ->sum(function ($loan) {
                return $loan->total_amount - $loan->paid_amount;
            });

        $totalReceivable = $loans->where('type', 'receivable')
            ->where('status', 'active')
            ->sum(function ($loan) {
                return $loan->total_amount - $loan->paid_amount;
            });

        $monthlyPaid = \App\Models\LoanInstallment::whereHas('loan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('type', 'payable');
        })
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('paid_amount');

        $monthlyReceived = \App\Models\LoanInstallment::whereHas('loan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('type', 'receivable');
        })
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('paid_amount');

        return view('livewire.loans.index', [
            'loans' => $loans,
            'totalPayable' => $totalPayable,
            'totalReceivable' => $totalReceivable,
            'monthlyPaid' => $monthlyPaid,
            'monthlyReceived' => $monthlyReceived,
            'totalLoansCount' => $totalLoansCount,
            'loanLimit' => $loanLimit,
            'isPremium' => $isPremium,
            'canCreate' => $isPremium || $totalLoansCount < $loanLimit,
        ]);
    }
}
