<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'user_id',
        'parent_id'
    ];

    protected $attributes = [
        'icon' => 'circle',
        'color' => '#3B82F6'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getFullNameAttribute()
    {
        return $this->parent ? "{$this->parent->name} > {$this->name}" : $this->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
