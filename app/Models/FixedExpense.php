<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FixedExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'category_id',
        'title',
        'description',
        'amount',
        'due_day',
        'is_active',
        'start_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'due_day' => 'integer',
        'start_date' => 'date'
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

        // Check if the expense starts in the future
        if ($this->start_date && $this->start_date->isFuture() && !$this->start_date->isSameMonth($now)) {
            return 'not_started';
        }

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
