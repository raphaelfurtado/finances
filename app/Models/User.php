<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, \Laravel\Cashier\Billable, \NotificationChannels\WebPush\HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'is_premium',
        'is_admin',
        'premium_activated_at',
        'premium_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'is_admin' => 'boolean',
            'premium_activated_at' => 'datetime',
            'premium_expires_at' => 'datetime',
        ];
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Premium methods
    public function isPremium(): bool
    {
        if ($this->subscribed('premium')) {
            return true;
        }

        if ($this->is_premium) {
            // Check if premium has expired
            if ($this->premium_expires_at && $this->premium_expires_at->isPast()) {
                $this->update(['is_premium' => false]);
                return false;
            }
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function activatePremium(int $months = 1): void
    {
        $this->update([
            'is_premium' => true,
            'premium_activated_at' => now(),
            'premium_expires_at' => now()->addMonths($months),
        ]);
    }

    public function deactivatePremium(): void
    {
        $this->update([
            'is_premium' => false,
            'premium_expires_at' => null,
        ]);
    }

    public function premiumDaysRemaining(): int
    {
        if (!$this->premium_expires_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->premium_expires_at, false));
    }
}
