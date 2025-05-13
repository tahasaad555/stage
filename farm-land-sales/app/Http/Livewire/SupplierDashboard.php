<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\MaterielFermierAgricole;
use App\Models\Transaction;
use App\Models\Message;

class SupplierDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $fournisseur = $user->fournisseur;
        
        $myEquipment = MaterielFermierAgricole::where('fournisseurId', $fournisseur->id)->get();
        $pendingOrders = Transaction::whereHas('materiel', function($query) use ($fournisseur) {
                            $query->where('fournisseurId', $fournisseur->id);
                        })
                        ->where('statut', 'Pending')
                        ->get();
                        
        $unreadMessages = Message::where('destinataireId', $fournisseur->id)
                                ->where('lu', false)
                                ->count();
        
        $totalSales = Transaction::whereHas('materiel', function($query) use ($fournisseur) {
                            $query->where('fournisseurId', $fournisseur->id);
                        })
                        ->where('statut', 'Completed')
                        ->sum('montant');
        
        return view('livewire.supplier-dashboard', [
            'fournisseur' => $fournisseur,
            'myEquipment' => $myEquipment,
            'pendingOrders' => $pendingOrders,
            'unreadMessages' => $unreadMessages,
            'totalSales' => $totalSales
        ]);
    }
}