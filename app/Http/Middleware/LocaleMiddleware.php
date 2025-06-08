<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get locale from settings table directly
            $locale = Cache::remember('app_locale', 3600, function () {
                $setting = DB::table('settings')->where('key', 'locale')->first();
                return $setting ? $setting->value : config('app.locale', 'en');
            });
            
            // Set application locale
            app()->setLocale($locale);
            
            // Get timezone from settings
            $timezone = Cache::remember('app_timezone', 3600, function () {
                $setting = DB::table('settings')->where('key', 'timezone')->first();
                return $setting ? $setting->value : config('app.timezone', 'UTC');
            });
            
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
            
        } catch (\Exception $e) {
            // Fallback to default values if database is not available
            app()->setLocale(config('app.locale', 'en'));
        }
        
        return $next($request);
    }
}