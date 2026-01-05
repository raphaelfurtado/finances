<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FixedExpense extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'amount',
        'due_day',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'due_day' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getStatusThisMonthAttribute()
    {
        $now = Carbon::now();

        $hasTransaction = $this->transactions()
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->exists();

        if ($hasTransaction) {
            return 'paid';
        }

        if ($now->day > $this->due_day) {
            return 'overdue';
        }

        if (($this->due_day - $now->day) <= 2) {
            return 'due_soon';
        }

        return 'pending';
    }
}
