<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // Helper method to get a setting
    public static function get(string $key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // Helper method to set a setting
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("app_setting_{$key}");
    }

    // Check if maintenance mode is active
    public static function isMaintenanceMode(): bool
    {
        return self::get('maintenance_mode', 'false') === 'true';
    }

    // Check if global banner is active
    public static function isBannerActive(): bool
    {
        return self::get('global_banner_active', 'false') === 'true';
    }

    // Get banner data
    public static function getBanner(): ?array
    {
        if (!self::isBannerActive()) {
            return null;
        }

        return [
            'message' => self::get('global_banner_message', ''),
            'type' => self::get('global_banner_type', 'info'),
        ];
    }

    // Get plan price
    public static function getPlanPrice(): string
    {
        return self::get('plan_price', '9,90');
    }

    // Get free user loan limit
    public static function getFreeLoanLimit(): int
    {
        return (int) self::get('free_loan_limit', '2');
    }

    // Get free user transaction limit
    public static function getFreePlanLimit(): int
    {
        return (int) self::get('free_plan_limit', '50');
    }

    // Get legacy user transaction limit
    public static function getLegacyPlanLimit(): int
    {
        return (int) self::get('legacy_plan_limit', '70');
    }

    // Get legacy user cutoff date
    public static function getLegacyCutoffDate(): string
    {
        return self::get('legacy_cutoff_date', '2025-01-01');
    }
}
