<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'rating',
        'is_approved',
        'show_avatar',
        'show_profession',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'show_avatar' => 'boolean',
        'show_profession' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
