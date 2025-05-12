<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Annonce;
use App\Models\Transaction;

class AdminDashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $totalAnnouncements = Annonce::count();
        $totalTransactions = Transaction::count();
        $recentTransactions = Transaction::latest()->take(5)->get();
        
        return view('livewire.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalAnnouncements' => $totalAnnouncements,
            'totalTransactions' => $totalTransactions,
            'recentTransactions' => $recentTransactions
        ]);
    }
    
    public function approveAnnouncement($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->estActif = true;
        $annonce->save();
        
        session()->flash('message', 'Announcement approved successfully.');
    }
    
    public function rejectAnnouncement($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->estActif = false;
        $annonce->save();
        
        session()->flash('message', 'Announcement rejected.');
    }
}