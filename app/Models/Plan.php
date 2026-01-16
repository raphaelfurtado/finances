<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'stripe_id',
        'price',
        'interval',
        'active',
        'recommended',
    ];

    protected $casts = [
        'active' => 'boolean',
        'recommended' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function benefits(): HasMany
    {
        return $this->hasMany(PlanBenefit::class);
    }
}
