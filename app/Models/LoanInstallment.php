<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LoanInstallment extends Model
{
    /** @use HasFactory<\Database\Factories\LoanInstallmentFactory> */
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'amount',
        'paid_amount',
        'paid_at',
        'status',
        'waived_penalty',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'waived_penalty' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function isOverdue()
    {
        $dueDate = Carbon::parse($this->due_date);
        return $dueDate->isPast() && !$dueDate->isToday() && $this->status !== 'paid';
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return (int) now()->diffInDays($this->due_date);
    }

    public function getCurrentAmountAttribute()
    {
        if ($this->status === 'paid' || $this->waived_penalty) {
            return $this->amount;
        }

        if (!$this->isOverdue()) {
            return $this->amount;
        }

        $baseAmount = (float) $this->amount;
        $loan = $this->loan;
        $totalInterest = 0;

        // Apply late fee percentage
        if ($loan->late_fee_percentage > 0) {
            $totalInterest += $baseAmount * ($loan->late_fee_percentage / 100);
        }

        // Apply daily interest rate
        if ($loan->daily_interest_rate > 0) {
            $days = $this->days_overdue;
            $totalInterest += $baseAmount * ($loan->daily_interest_rate / 100) * $days;
        }

        return round($baseAmount + $totalInterest, 2);
    }
}
