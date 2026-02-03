<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'amount',
        'description',
        'date',
        'payment_method',
        'status',
        'is_recurring',
        'observation',
        'receipt_path',
        'fixed_expense_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'immutable_datetime',
        'is_recurring' => 'boolean'
    ];

    public function fixedExpense()
    {
        return $this->belongsTo(FixedExpense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('date', $month)
            ->whereYear('date', $year);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }
}
