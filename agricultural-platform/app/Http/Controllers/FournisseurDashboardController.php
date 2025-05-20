<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Models\TerreAgricole;
use App\Models\Message;
use App\Models\Avis;

class FournisseurDashboardController extends Controller
{
    /**
     * Show the fournisseur dashboard.
     */
    public function index()
    {
        // Ensure the user is a fournisseur
        if (!auth()->user()->isFournisseur()) {
            abort(403, 'Unauthorized action.');
        }
        
        $fournisseur = auth()->user()->fournisseur;
        
        // Get count statistics
        $stats = [
            'annonces' => Annonce::where('fournisseur_id', $fournisseur->id)->count(),
            'annoncesActives' => Annonce::where('fournisseur_id', $fournisseur->id)->where('estActive', true)->count(),
            'transactions' => Transaction::where('fournisseur_id', $fournisseur->id)->count(),
            'messages' => Message::where('fournisseur_id', $fournisseur->id)->count(),
            'avis' => Avis::where('fournisseur_id', $fournisseur->id)->count(),
        ];
        
        // Get transaction statistics
        $transactionStats = [
            'total' => Transaction::where('fournisseur_id', $fournisseur->id)->count(),
            'enAttente' => Transaction::where('fournisseur_id', $fournisseur->id)->where('statusTransaction', 'en_attente')->count(),
            'completees' => Transaction::where('fournisseur_id', $fournisseur->id)->where('statusTransaction', 'completee')->count(),
            'annulees' => Transaction::where('fournisseur_id', $fournisseur->id)->where('statusTransaction', 'annulee')->count(),
            'montantTotal' => Transaction::where('fournisseur_id', $fournisseur->id)->where('statusTransaction', 'completee')->sum('montant'),
        ];
        
        // Calculate average rating
        $averageRating = Avis::where('fournisseur_id', $fournisseur->id)->avg('note') ?? 0;
        
        // Get recent transactions
        $recentTransactions = Transaction::with('client.utilisateur')
            ->where('fournisseur_id', $fournisseur->id)
            ->latest('dateTransaction')
            ->limit(5)
            ->get();
        
        // Get recent annonces
        $recentAnnonces = Annonce::with('terreAgricole')
            ->where('fournisseur_id', $fournisseur->id)
            ->latest('dateCreation')
            ->limit(5)
            ->get();
        
        // Get unread messages
        $unreadMessages = Message::with('client.utilisateur')
            ->where('fournisseur_id', $fournisseur->id)
            ->where('lu', false)
            ->orderBy('dateHeure', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboards.fournisseur', compact(
            'stats', 
            'transactionStats', 
            'averageRating', 
            'recentTransactions', 
            'recentAnnonces',
            'unreadMessages'
        ));
    }
}