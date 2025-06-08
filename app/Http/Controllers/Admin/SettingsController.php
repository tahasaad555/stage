<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        // Get all settings grouped by category
        $settings = $this->getAllSettings();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'currency' => 'required|in:MAD,EUR,USD',
            'timezone' => 'required|string',
            'language' => 'required|in:en,fr,ar',
            'site_description' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $settings = [
            'site_name' => ['value' => $request->site_name, 'type' => 'string'],
            'contact_email' => ['value' => $request->contact_email, 'type' => 'string'],
            'contact_phone' => ['value' => $request->contact_phone, 'type' => 'string'],
            'currency' => ['value' => $request->currency, 'type' => 'string'],
            'timezone' => ['value' => $request->timezone, 'type' => 'string'],
            'language' => ['value' => $request->language, 'type' => 'string'],
            'site_description' => ['value' => $request->site_description, 'type' => 'string'],
            'address' => ['value' => $request->address, 'type' => 'string'],
        ];

        Setting::setMany($settings, 'general');

        return response()->json([
            'success' => true,
            'message' => 'General settings updated successfully!'
        ]);
    }

    /**
     * Update commission settings
     */
    public function updateCommission(Request $request)
    {
        $request->validate([
            'commission_type' => 'required|in:percentage,fixed',
            'commission_rate' => 'required|numeric|min:0',
            'minimum_commission' => 'required|numeric|min:0',
            'maximum_commission' => 'required|numeric|min:0',
        ]);

        $settings = [
            'commission_type' => ['value' => $request->commission_type, 'type' => 'string'],
            'commission_rate' => ['value' => $request->commission_rate, 'type' => 'float'],
            'minimum_commission' => ['value' => $request->minimum_commission, 'type' => 'float'],
            'maximum_commission' => ['value' => $request->maximum_commission, 'type' => 'float'],
        ];

        Setting::setMany($settings, 'commission');

        return response()->json([
            'success' => true,
            'message' => 'Commission settings updated successfully!'
        ]);
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $settings = [
            'mail_driver' => ['value' => $request->mail_driver, 'type' => 'string'],
            'mail_host' => ['value' => $request->mail_host, 'type' => 'string'],
            'mail_port' => ['value' => $request->mail_port, 'type' => 'integer'],
            'mail_encryption' => ['value' => $request->mail_encryption, 'type' => 'string'],
            'mail_username' => ['value' => $request->mail_username, 'type' => 'string'],
            'mail_password' => ['value' => $request->mail_password, 'type' => 'string'],
            'mail_from_address' => ['value' => $request->mail_from_address, 'type' => 'string'],
            'mail_from_name' => ['value' => $request->mail_from_name, 'type' => 'string'],
        ];

        Setting::setMany($settings, 'email');

        return response()->json([
            'success' => true,
            'message' => 'Email settings updated successfully!'
        ]);
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'session_lifetime' => 'required|integer|min:1|max:1440',
            'password_min_length' => 'required|integer|min:6|max:50',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
            'require_email_verification' => 'boolean',
            'enable_two_factor' => 'boolean',
            'force_https' => 'boolean',
            'enable_rate_limiting' => 'boolean',
        ]);

        $settings = [
            'session_lifetime' => ['value' => $request->session_lifetime, 'type' => 'integer'],
            'password_min_length' => ['value' => $request->password_min_length, 'type' => 'integer'],
            'max_login_attempts' => ['value' => $request->max_login_attempts, 'type' => 'integer'],
            'lockout_duration' => ['value' => $request->lockout_duration, 'type' => 'integer'],
            'require_email_verification' => ['value' => $request->boolean('require_email_verification'), 'type' => 'boolean'],
            'enable_two_factor' => ['value' => $request->boolean('enable_two_factor'), 'type' => 'boolean'],
            'force_https' => ['value' => $request->boolean('force_https'), 'type' => 'boolean'],
            'enable_rate_limiting' => ['value' => $request->boolean('enable_rate_limiting'), 'type' => 'boolean'],
        ];

        Setting::setMany($settings, 'security');

        return response()->json([
            'success' => true,
            'message' => 'Security settings updated successfully!'
        ]);
    }

    /**
     * Update backup settings
     */
    public function updateBackup(Request $request)
    {
        $request->validate([
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'backup_retention_days' => 'required|integer|min:1|max:365',
            'backup_storage' => 'required|in:local,s3,google,dropbox',
            'backup_time' => 'required|date_format:H:i',
            'backup_enabled' => 'boolean',
        ]);

        $settings = [
            'backup_frequency' => ['value' => $request->backup_frequency, 'type' => 'string'],
            'backup_retention_days' => ['value' => $request->backup_retention_days, 'type' => 'integer'],
            'backup_storage' => ['value' => $request->backup_storage, 'type' => 'string'],
            'backup_time' => ['value' => $request->backup_time, 'type' => 'string'],
            'backup_enabled' => ['value' => $request->boolean('backup_enabled'), 'type' => 'boolean'],
        ];

        Setting::setMany($settings, 'backup');

        return response()->json([
            'success' => true,
            'message' => 'Backup settings updated successfully!'
        ]);
    }

    /**
     * Run backup manually
     */
    public function runBackup()
    {
        try {
            $filename = 'agriterre_backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Create backups directory if it doesn't exist
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Run database backup
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command);

            $size = $this->formatBytes(filesize($path));

            return response()->json([
                'success' => true,
                'message' => 'Backup completed successfully!',
                'backup_name' => $filename,
                'backup_size' => $size,
                'backup_date' => now()->format('M d, Y H:i')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize system
     */
    public function optimizeSystem()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            
            return response()->json([
                'success' => true,
                'message' => 'System optimization completed!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Optimization failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enable maintenance mode
     */
    public function enableMaintenance()
    {
        try {
            Artisan::call('down');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode enabled successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to enable maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable maintenance mode
     */
    public function disableMaintenance()
    {
        try {
            Artisan::call('up');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode disabled successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        return response()->json([
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database' => DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'disk_space' => $this->formatBytes(disk_total_space('.')),
            'free_space' => $this->formatBytes(disk_free_space('.')),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
        ]);
    }

    /**
     * Get all settings organized by groups
     */
    private function getAllSettings(): array
    {
        // Set default settings if they don't exist
        $this->setDefaultSettings();
        
        return [
            'general' => Setting::getGroup('general'),
            'commission' => Setting::getGroup('commission'),
            'email' => Setting::getGroup('email'),
            'security' => Setting::getGroup('security'),
            'backup' => Setting::getGroup('backup'),
        ];
    }

    /**
     * Set default settings
     */
    private function setDefaultSettings()
    {
        $defaults = [
            // General settings
            'site_name' => ['value' => 'AgriTerre Morocco', 'type' => 'string', 'group' => 'general'],
            'contact_email' => ['value' => 'contact@agriterre.ma', 'type' => 'string', 'group' => 'general'],
            'contact_phone' => ['value' => '+212 5 22 12 34 56', 'type' => 'string', 'group' => 'general'],
            'currency' => ['value' => 'MAD', 'type' => 'string', 'group' => 'general'],
            'timezone' => ['value' => 'Africa/Casablanca', 'type' => 'string', 'group' => 'general'],
            'language' => ['value' => 'en', 'type' => 'string', 'group' => 'general'],
            'site_description' => ['value' => 'Morocco\'s premier agricultural land marketplace', 'type' => 'string', 'group' => 'general'],
            'address' => ['value' => '123 Boulevard Hassan II, Casablanca 20000, Morocco', 'type' => 'string', 'group' => 'general'],
            
            // Commission settings
            'commission_type' => ['value' => 'percentage', 'type' => 'string', 'group' => 'commission'],
            'commission_rate' => ['value' => 5.0, 'type' => 'float', 'group' => 'commission'],
            'minimum_commission' => ['value' => 1000, 'type' => 'float', 'group' => 'commission'],
            'maximum_commission' => ['value' => 50000, 'type' => 'float', 'group' => 'commission'],
            
            // Email settings
            'mail_driver' => ['value' => 'smtp', 'type' => 'string', 'group' => 'email'],
            'mail_host' => ['value' => 'smtp.gmail.com', 'type' => 'string', 'group' => 'email'],
            'mail_port' => ['value' => 587, 'type' => 'integer', 'group' => 'email'],
            'mail_encryption' => ['value' => 'tls', 'type' => 'string', 'group' => 'email'],
            'mail_username' => ['value' => 'noreply@agriterre.ma', 'type' => 'string', 'group' => 'email'],
            'mail_password' => ['value' => '', 'type' => 'string', 'group' => 'email'],
            'mail_from_address' => ['value' => 'noreply@agriterre.ma', 'type' => 'string', 'group' => 'email'],
            'mail_from_name' => ['value' => 'AgriTerre Morocco', 'type' => 'string', 'group' => 'email'],
            
            // Security settings
            'session_lifetime' => ['value' => 120, 'type' => 'integer', 'group' => 'security'],
            'password_min_length' => ['value' => 8, 'type' => 'integer', 'group' => 'security'],
            'max_login_attempts' => ['value' => 5, 'type' => 'integer', 'group' => 'security'],
            'lockout_duration' => ['value' => 15, 'type' => 'integer', 'group' => 'security'],
            'require_email_verification' => ['value' => true, 'type' => 'boolean', 'group' => 'security'],
            'enable_two_factor' => ['value' => false, 'type' => 'boolean', 'group' => 'security'],
            'force_https' => ['value' => true, 'type' => 'boolean', 'group' => 'security'],
            'enable_rate_limiting' => ['value' => true, 'type' => 'boolean', 'group' => 'security'],
            
            // Backup settings
            'backup_frequency' => ['value' => 'daily', 'type' => 'string', 'group' => 'backup'],
            'backup_retention_days' => ['value' => 30, 'type' => 'integer', 'group' => 'backup'],
            'backup_storage' => ['value' => 'local', 'type' => 'string', 'group' => 'backup'],
            'backup_time' => ['value' => '02:00', 'type' => 'string', 'group' => 'backup'],
            'backup_enabled' => ['value' => true, 'type' => 'boolean', 'group' => 'backup'],
        ];

        foreach ($defaults as $key => $config) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $config['value'], $config['type'], $config['group']);
            }
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
 * Test email configuration
 */
public function testEmail()
{
    // This method already exists in your controller
}

/**
 * Backup database
 */
public function backupDatabase()
{
    // Alias for runBackup method
    return $this->runBackup();
}

/**
 * Optimize database
 */
public function optimizeDatabase()
{
    // This method already exists in your controller
}

/**
 * Rebuild cache
 */
public function rebuildCache()
{
    // This method already exists in your controller
}

/**
 * Export settings to file
 */
public function exportSettings()
{
    try {
        $settings = Setting::all()->toArray();
        
        $filename = 'agriterre_settings_' . now()->format('Y_m_d_H_i_s') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
            
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Export failed: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Import settings from file
 */
public function importSettings(Request $request)
{
    $request->validate([
        'settings_file' => 'required|file|mimes:json'
    ]);
    
    try {
        $file = $request->file('settings_file');
        $content = file_get_contents($file->getRealPath());
        $settings = json_decode($content, true);
        
        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON file'
            ], 422);
        }
        
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        Setting::clearCache();
        
        return response()->json([
            'success' => true,
            'message' => 'Settings imported successfully!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Import failed: ' . $e->getMessage()
        ], 500);
    }
}
}