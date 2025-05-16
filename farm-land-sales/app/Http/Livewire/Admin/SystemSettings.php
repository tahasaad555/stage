<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SystemSettings extends Component
{
    public $siteName;
    public $siteEmail;
    public $contactPhone;
    public $address;
    public $commission;
    public $maintenanceMode = false;
    public $requireApproval = true;
    
    public function mount()
    {
        // Load settings from database or cache
        $this->loadSettings();
    }
    
    public function render()
    {
        return view('livewire.admin.system-settings');
    }
    
    private function loadSettings()
    {
        $settings = Cache::remember('system_settings', 60 * 24, function () {
            return Setting::all()->keyBy('key')->map(function ($setting) {
                return $setting->value;
            })->toArray();
        });
        
        // Map settings to properties
        $this->siteName = $settings['site_name'] ?? 'AgriLand';
        $this->siteEmail = $settings['site_email'] ?? 'info@agriland.com';
        $this->contactPhone = $settings['contact_phone'] ?? '';
        $this->address = $settings['address'] ?? '';
        $this->commission = $settings['commission'] ?? '5';
        $this->maintenanceMode = ($settings['maintenance_mode'] ?? 'false') === 'true';
        $this->requireApproval = ($settings['require_approval'] ?? 'true') === 'true';
    }
    
    public function saveSettings()
    {
        $this->validate([
            'siteName' => 'required|string|max:255',
            'siteEmail' => 'required|email|max:255',
            'contactPhone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'commission' => 'required|numeric|min:0|max:100',
        ]);
        
        // Save settings to database
        $settings = [
            'site_name' => $this->siteName,
            'site_email' => $this->siteEmail,
            'contact_phone' => $this->contactPhone,
            'address' => $this->address,
            'commission' => $this->commission,
            'maintenance_mode' => $this->maintenanceMode ? 'true' : 'false',
            'require_approval' => $this->requireApproval ? 'true' : 'false',
        ];
        
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        // Clear cache
        Cache::forget('system_settings');
        
        session()->flash('message', 'Settings saved successfully.');
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}