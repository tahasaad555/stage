<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TerrainAgricole;
use App\Models\MaterielFermierAgricole;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use Carbon\Carbon;

class Reports extends Component
{
    public $period = 'monthly';
    public $startDate;
    public $endDate;
    
    public function mount()
    {
        $this->startDate = Carbon::now()->subMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }
    
    public function render()
    {
        // Get date range based on selected period
        $dates = $this->getDateRange();
        
        // Calculate statistics
        $totalSales = Transaction::whereBetween('dateTransaction', [$dates['start'], $dates['end']])
                                ->where('statut', 'Completed')
                                ->sum('montant');
                                
        $totalCommissions = Transaction::whereBetween('dateTransaction', [$dates['start'], $dates['end']])
                                     ->where('statut', 'Completed')
                                     ->sum('commission');
                                     
        $newUsers = User::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        
        $newClients = Client::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        
        $newSuppliers = Fournisseur::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        
        $newLands = TerrainAgricole::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        
        $newEquipment = MaterielFermierAgricole::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        
        // Get sales transactions by day for chart
        $salesByDay = Transaction::selectRaw('DATE(dateTransaction) as date, SUM(montant) as total')
                                ->whereBetween('dateTransaction', [$dates['start'], $dates['end']])
                                ->where('statut', 'Completed')
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get()
                                ->keyBy('date')
                                ->map(function ($item) {
                                    return $item->total;
                                })
                                ->toArray();
        
        // Generate dates array for the chart
        $period = new \DatePeriod(
            new \DateTime($dates['start']),
            new \DateInterval('P1D'),
            new \DateTime($dates['end'])
        );
        
        $chartData = [];
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartData[] = [
                'date' => $dateString,
                'sales' => $salesByDay[$dateString] ?? 0,
            ];
        }
        
        // Get top selling categories
        $landSales = Transaction::whereHas('annonce', function($query) {
                                 $query->where('type', 'land');
                             })
                             ->whereBetween('dateTransaction', [$dates['start'], $dates['end']])
                             ->where('statut', 'Completed')
                             ->sum('montant');
                             
        $equipmentSales = Transaction::whereHas('annonce', function($query) {
                                    $query->where('type', 'equipment');
                                })
                                ->whereBetween('dateTransaction', [$dates['start'], $dates['end']])
                                ->where('statut', 'Completed')
                                ->sum('montant');
        
        return view('livewire.admin.reports', [
            'totalSales' => $totalSales,
            'totalCommissions' => $totalCommissions,
            'newUsers' => $newUsers,
            'newClients' => $newClients,
            'newSuppliers' => $newSuppliers,
            'newLands' => $newLands,
            'newEquipment' => $newEquipment,
            'chartData' => $chartData,
            'landSales' => $landSales,
            'equipmentSales' => $equipmentSales,
        ]);
    }
    
    public function setPeriod($period)
    {
        $this->period = $period;
    }
    
    public function applyCustomDateRange()
    {
        $this->period = 'custom';
    }
    
    private function getDateRange()
    {
        $now = Carbon::now();
        
        switch ($this->period) {
            case 'weekly':
                return [
                    'start' => $now->copy()->subWeek()->startOfDay(),
                    'end' => $now->endOfDay(),
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->subMonth()->startOfDay(),
                    'end' => $now->endOfDay(),
                ];
            case 'yearly':
                return [
                    'start' => $now->copy()->subYear()->startOfDay(),
                    'end' => $now->endOfDay(),
                ];
            case 'custom':
                return [
                    'start' => Carbon::parse($this->startDate)->startOfDay(),
                    'end' => Carbon::parse($this->endDate)->endOfDay(),
                ];
            default:
                return [
                    'start' => $now->copy()->subMonth()->startOfDay(),
                    'end' => $now->endOfDay(),
                ];
        }
    }
}