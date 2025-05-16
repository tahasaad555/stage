<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Models\TerrainAgricole;
use App\Models\MaterielFermierAgricole;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalClients;
    public $totalSuppliers;
    public $totalAnnouncements;
    public $totalTransactions;
    public $totalRevenue;
    public $totalCommissions;
    public $pendingAnnouncements;
    
    public function mount()
    {
        $this->loadStatistics();
    }
    
    public function render()
    {
        $recentTransactions = Transaction::with(['client'])
                                      ->latest('dateTransaction')
                                      ->take(5)
                                      ->get();
                                      
        $pendingAnnouncementsPreview = Annonce::where('estActif', false)
                                            ->latest()
                                            ->take(5)
                                            ->get();
        
        return view('livewire.admin.admin-dashboard', [
            'recentTransactions' => $recentTransactions,
            'pendingAnnouncementsPreview' => $pendingAnnouncementsPreview
        ]);
    }
    
    private function loadStatistics()
    {
        $this->totalUsers = User::count();
        $this->totalClients = User::has('client')->count();
        $this->totalSuppliers = User::has('fournisseur')->count();
        $this->totalAnnouncements = Annonce::count();
        $this->totalTransactions = Transaction::count();
        $this->totalRevenue = Transaction::where('statut', 'Completed')->sum('montant');
        $this->totalCommissions = Transaction::where('statut', 'Completed')->sum('commission');
        $this->pendingAnnouncements = Annonce::where('estActif', false)->count();
    }
    
    public function approveAnnouncement($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->estActif = true;
        $annonce->save();
        
        session()->flash('message', 'Announcement approved successfully.');
        $this->loadStatistics();
    }
    
    public function rejectAnnouncement($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->estActif = false;
        $annonce->save();
        
        session()->flash('message', 'Announcement rejected.');
        $this->loadStatistics();
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}