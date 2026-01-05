<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'counterparty_name',
        'counterparty_phone',
        'description',
        'notes',
        'principal_amount',
        'interest_rate',
        'late_fee_percentage',
        'daily_interest_rate',
        'interest_calculation_type',
        'total_amount',
        'installments_count',
        'start_date',
        'status',
        'installment_frequency',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'late_fee_percentage' => 'decimal:2',
        'daily_interest_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->installments()->sum('paid_amount');
    }

    public function getProgressAttribute()
    {
        if ($this->total_amount <= 0)
            return 0;
        return round(($this->paid_amount / $this->total_amount) * 100);
    }

    public function getPaidInstallmentsCountAttribute()
    {
        return $this->installments()->where('status', 'paid')->count();
    }

    public function getRemainingInstallmentsCountAttribute()
    {
        return $this->installments_count - $this->paid_installments_count;
    }
}
