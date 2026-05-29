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

        // Use eager-loaded transactions when available to avoid an N+1 query per expense.
        if ($this->relationLoaded('transactions')) {
            $hasTransaction = $this->transactions->contains(
                fn($t) => $t->date && $t->date->month === $now->month && $t->date->year === $now->year
            );
        } else {
            $hasTransaction = $this->transactions()
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->exists();
        }

        if ($hasTransaction) {
            return 'paid';
        }

        // Clamp the due day so a bill due on the 31st still resolves in short months (Feb, etc.)
        $effectiveDay = min($this->due_day, $now->daysInMonth);

        if ($now->day > $effectiveDay) {
            return 'overdue';
        }

        if (($effectiveDay - $now->day) <= 2) {
            return 'due_soon';
        }

        return 'pending';
    }

    /**
     * The due date for this expense within the reference month, clamped to the
     * last day of short months (e.g. a due_day of 31 falls on Feb 28).
     */
    public function dueDateFor(Carbon $reference): Carbon
    {
        $day = min($this->due_day, $reference->daysInMonth);

        return $reference->copy()->startOfMonth()->setDay($day)->startOfDay();
    }
}
