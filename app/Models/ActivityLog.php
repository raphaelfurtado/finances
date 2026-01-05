<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'method',
        'action_name',
        'feature_area',
        'ip_address',
        'user_agent',
        'device',
        'browser',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
