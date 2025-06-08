<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        return Cache::get("setting_{$key}", $default);
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        Cache::forever("setting_{$key}", $value);
    }

    /**
     * Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', 'AgriTerre');
    }

    /**
     * Get contact email
     */
    public static function contactEmail()
    {
        return self::get('contact_email', 'contact@agriterre.com');
    }

    /**
     * Get commission rate
     */
    public static function commissionRate()
    {
        return self::get('commission_rate', 5.0);
    }

    /**
     * Get currency
     */
    public static function currency()
    {
        return self::get('currency', 'MAD');
    }

    /**
     * Calculate commission for an amount
     */
    public static function calculateCommission($amount)
    {
        $rate = self::get('commission_rate', 5.0);
        $type = self::get('commission_type', 'percentage');
        $minimum = self::get('minimum_commission', 100);

        if ($type === 'percentage') {
            $commission = ($amount * $rate) / 100;
            return max($commission, $minimum);
        }

        return $rate;
    }

    /**
     * Check if feature is enabled
     */
    public static function isFeatureEnabled($feature)
    {
        return self::get($feature, false);
    }
}