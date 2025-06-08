<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\SettingsHelper;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the settings helper
        $this->app->singleton('settings', function () {
            return new SettingsHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $view->with([
                'siteName' => SettingsHelper::siteName(),
                'currency' => SettingsHelper::currency(),
                'contactEmail' => SettingsHelper::contactEmail(),
            ]);
        });

        // Load default settings on first boot
        $this->loadDefaultSettings();
    }

    /**
     * Load default settings if they don't exist
     */
    private function loadDefaultSettings()
    {
        $defaults = [
            'site_name' => 'AgriTerre',
            'site_description' => 'Agricultural Land Marketplace in Morocco',
            'contact_email' => 'contact@agriterre.com',
            'contact_phone' => '+212 123 456 789',
            'address' => 'Casablanca, Morocco',
            'currency' => 'MAD',
            'timezone' => 'Africa/Casablanca',
            'language' => 'en',
            'commission_rate' => 5.0,
            'minimum_commission' => 100,
            'commission_type' => 'percentage',
            'session_lifetime' => 120,
            'password_min_length' => 8,
            'require_email_verification' => true,
            'enable_two_factor' => false,
            'max_login_attempts' => 5,
            'lockout_duration' => 15,
            'backup_enabled' => true,
            'backup_frequency' => 'weekly',
            'backup_retention_days' => 30,
            'backup_storage' => 'local',
        ];

        foreach ($defaults as $key => $value) {
            if (SettingsHelper::get($key) === null) {
                SettingsHelper::set($key, $value);
            }
        }
    }
}