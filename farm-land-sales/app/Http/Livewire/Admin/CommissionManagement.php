<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class CommissionManagement extends Component
{
    public $currentCommissionRate;
    public $newCommissionRate;
    public $totalCommissionEarned;
    public $pendingCommissions;
    public $monthlySummary = [];
    
    protected $rules = [
        'newCommissionRate' => 'required|numeric|between:0,100',
    ];
    
    public function mount()
    {
        $this->loadCurrentCommission();
        $this->calculateStatistics();
    }
    
    public function render()
    {
        $recentTransactions = Transaction::where('statut', 'Completed')
                                      ->latest('dateTransaction')
                                      ->take(10)
                                      ->get();
        
        return view('livewire.admin.commission-management', [
            'recentTransactions' => $recentTransactions
        ]);
    }
    
    public function loadCurrentCommission()
    {
        $setting = Setting::where('key', 'commission')->first();
        
        if ($setting) {
            $this->currentCommissionRate = $setting->value;
        } else {
            // Default commission if not set
            $this->currentCommissionRate = 5;
        }
        
        $this->newCommissionRate = $this->currentCommissionRate;
    }
    
    public function calculateStatistics()
    {
        // Calculate total commission earned
        $this->totalCommissionEarned = Transaction::where('statut', 'Completed')
                                               ->sum('commission');
        
        // Calculate pending commissions
        $this->pendingCommissions = Transaction::whereIn('statut', ['Pending', 'Processing', 'Shipped'])
                                           ->sum('commission');
        
        // Calculate monthly summary for the last 6 months
        $this->monthlySummary = [];
        for ($i = 0; $i < 6; $i++) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $commission = Transaction::where('statut', 'Completed')
                                  ->whereBetween('dateTransaction', [$startOfMonth, $endOfMonth])
                                  ->sum('commission');
            
            $this->monthlySummary[] = [
                'month' => $month->format('F Y'),
                'commission' => $commission
            ];
        }
        
        // Reverse to show oldest first
        $this->monthlySummary = array_reverse($this->monthlySummary);
    }
    
    public function updateCommissionRate()
    {
        $this->validate();
        
        // Update or create the commission setting
        Setting::updateOrCreate(
            ['key' => 'commission'],
            ['value' => $this->newCommissionRate]
        );
        
        // Clear the system settings cache
        Cache::forget('system_settings');
        
        $this->currentCommissionRate = $this->newCommissionRate;
        
        session()->flash('message', 'Commission rate updated successfully.');
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}