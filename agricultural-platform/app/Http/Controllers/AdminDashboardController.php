<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Models\Commission;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\TerreAgricole;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Ensure the user is an admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get count statistics
        $stats = [
            'clients' => Client::count(),
            'fournisseurs' => Fournisseur::count(),
            'annonces' => Annonce::count(),
            'terres' => TerreAgricole::count(),
        ];
        
        // Get transaction statistics
        $transactionStats = [
            'total' => Transaction::count(),
            'enAttente' => Transaction::where('statusTransaction', 'en_attente')->count(),
            'completees' => Transaction::where('statusTransaction', 'completee')->count(),
            'annulees' => Transaction::where('statusTransaction', 'annulee')->count(),
            'montantTotal' => Transaction::where('statusTransaction', 'completee')->sum('montant'),
            'commissionsTotal' => Commission::sum('montant'),
        ];
        
        // Get recent transactions
        $recentTransactions = Transaction::with(['client.utilisateur', 'fournisseur.utilisateur'])
            ->latest('dateTransaction')
            ->limit(10)
            ->get();
        
        // Get recent annonces
        $recentAnnonces = Annonce::with(['fournisseur.utilisateur', 'terreAgricole'])
            ->latest('dateCreation')
            ->limit(10)
            ->get();
        
        return view('dashboards.admin', compact('stats', 'transactionStats', 'recentTransactions', 'recentAnnonces'));
    }
}