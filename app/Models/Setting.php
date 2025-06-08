<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting.{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', string $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => static::prepareValue($value, $type),
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget("setting.{$key}");
        Cache::forget("settings.group.{$group}");

        return $setting;
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup(string $group): array
    {
        $cacheKey = "settings.group.{$group}";
        
        return Cache::remember($cacheKey, 3600, function () use ($group) {
            $settings = static::where('group', $group)->get();
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = static::castValue($setting->value, $setting->type);
            }
            
            return $result;
        });
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            $settings = static::all();
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = static::castValue($setting->value, $setting->type);
            }
            
            return $result;
        });
    }

    /**
     * Set multiple settings at once
     */
    public static function setMany(array $settings, string $group = 'general')
    {
        foreach ($settings as $key => $config) {
            if (is_array($config)) {
                static::set(
                    $key,
                    $config['value'],
                    $config['type'] ?? 'string',
                    $config['group'] ?? $group,
                    $config['description'] ?? null
                );
            } else {
                static::set($key, $config, 'string', $group);
            }
        }
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    protected static function prepareValue($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'json':
            case 'array':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('settings.all');
        
        $groups = static::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings.group.{$group}");
        }

        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting.{$key}");
        }
    }
}