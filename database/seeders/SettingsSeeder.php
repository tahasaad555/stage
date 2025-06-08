<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General settings
            [
                'key' => 'site_name',
                'value' => 'AgriTerre Morocco',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Name of the application'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@agriterre.ma',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Primary contact email address'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+212 5 22 12 34 56',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Primary contact phone number'
            ],
            [
                'key' => 'currency',
                'value' => 'MAD',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default currency code'
            ],
            [
                'key' => 'timezone',
                'value' => 'Africa/Casablanca',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone'
            ],
            [
                'key' => 'language',
                'value' => 'en',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default language'
            ],
            [
                'key' => 'site_description',
                'value' => 'Morocco\'s premier agricultural land marketplace, connecting farmers, investors, and landowners.',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Site description for SEO and branding'
            ],
            [
                'key' => 'address',
                'value' => '123 Boulevard Hassan II, Casablanca 20000, Morocco',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Physical business address'
            ],

            // Commission settings
            [
                'key' => 'commission_type',
                'value' => 'percentage',
                'type' => 'string',
                'group' => 'commission',
                'description' => 'Type of commission calculation (percentage or fixed)'
            ],
            [
                'key' => 'commission_rate',
                'value' => '5.0',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Commission rate percentage or fixed amount'
            ],
            [
                'key' => 'minimum_commission',
                'value' => '1000',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Minimum commission amount in MAD'
            ],
            [
                'key' => 'maximum_commission',
                'value' => '50000',
                'type' => 'float',
                'group' => 'commission',
                'description' => 'Maximum commission amount in MAD'
            ],

            // Email settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email driver (smtp, sendmail, mailgun)'
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP server hostname'
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'type' => 'integer',
                'group' => 'email',
                'description' => 'SMTP server port'
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email encryption method'
            ],
            [
                'key' => 'mail_username',
                'value' => 'noreply@agriterre.ma',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP username'
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP password'
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@agriterre.ma',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from email address'
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'AgriTerre Morocco',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default from name'
            ],

            // Security settings
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Session lifetime in minutes'
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Minimum password length'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Maximum login attempts before lockout'
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Lockout duration in minutes'
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Require email verification for new accounts'
            ],
            [
                'key' => 'enable_two_factor',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Enable two-factor authentication'
            ],
            [
                'key' => 'force_https',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Force HTTPS connections'
            ],
            [
                'key' => 'enable_rate_limiting',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Enable rate limiting for API requests'
            ],

            // Backup settings
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Backup frequency (daily, weekly, monthly)'
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'integer',
                'group' => 'backup',
                'description' => 'Number of days to retain backups'
            ],
            [
                'key' => 'backup_storage',
                'value' => 'local',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Backup storage location'
            ],
            [
                'key' => 'backup_time',
                'value' => '02:00',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Time to run automatic backups'
            ],
            [
                'key' => 'backup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'backup',
                'description' => 'Enable automatic backups'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}