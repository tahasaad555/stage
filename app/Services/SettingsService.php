<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    const CACHE_KEY = 'app_settings';
    const CACHE_DURATION = 24; // hours

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings
     */
    public static function all()
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(self::CACHE_DURATION), function () {
            $dbSettings = DB::table('settings')->pluck('value', 'key')->toArray();
            return $dbSettings;
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return Cache::remember(self::CACHE_KEY . '_' . $group, now()->addHours(self::CACHE_DURATION), function () use ($group) {
            return DB::table('settings')
                ->where('group', $group)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('key')
                ->toArray();
        });
    }

    /**
     * Get public settings (accessible by non-admin users)
     */
    public static function getPublic()
    {
        return Cache::remember(self::CACHE_KEY . '_public', now()->addHours(self::CACHE_DURATION), function () {
            return DB::table('settings')
                ->where('is_public', true)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        
        if ($setting) {
            // Convert value based on type
            $convertedValue = self::convertValue($value, $setting->type);
            
            DB::table('settings')
                ->where('key', $key)
                ->update([
                    'value' => $convertedValue,
                    'updated_at' => now()
                ]);
        } else {
            // Create new setting if it doesn't exist
            DB::table('settings')->insert([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => 'string',
                'group' => 'general',
                'label' => ucwords(str_replace('_', ' ', $key)),
                'is_public' => false,
                'is_editable' => true,
                'sort_order' => 999,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Clear cache to force reload
        self::clearCache();
    }

    /**
     * Convert value based on type
     */
    private static function convertValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? 'true' : 'false';
            case 'integer':
                return (string) intval($value);
            case 'float':
                return (string) floatval($value);
            case 'json':
                return is_array($value) ? json_encode($value) : $value;
            default:
                return (string) $value;
        }
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY . '_public');
        Cache::forget(self::CACHE_KEY . '_general');
        Cache::forget(self::CACHE_KEY . '_business');
        Cache::forget(self::CACHE_KEY . '_security');
        Cache::forget(self::CACHE_KEY . '_email');
    }

    /**
     * Get typed value (convert from string storage to proper type)
     */
    public static function getTyped($key, $default = null)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::convertFromStorage($setting->value, $setting->type);
    }

    /**
     * Convert value from storage format to proper type
     */
    private static function convertFromStorage($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value === 'true' || $value === '1' || $value === 1;
            case 'integer':
                return intval($value);
            case 'float':
                return floatval($value);
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }

    /**
     * Get all settings with metadata
     */
    public static function getAllWithMetadata()
    {
        return Cache::remember(self::CACHE_KEY . '_metadata', now()->addHours(self::CACHE_DURATION), function () {
            return DB::table('settings')
                ->orderBy('group')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('group')
                ->toArray();
        });
    }
}