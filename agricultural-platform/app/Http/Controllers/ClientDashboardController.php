<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Models\Favori;
use App\Models\Message;
use App\Models\Recherche;

class ClientDashboardController extends Controller
{
    /**
     * Show the client dashboard.
     */
    public function index()
    {
        // Ensure the user is a client
        if (!auth()->user()->isClient()) {
            abort(403, 'Unauthorized action.');
        }
        
        $client = auth()->user()->client;
        
        // Get count statistics
        $stats = [
            'favoris' => Favori::where('client_id', $client->id)->count(),
            'transactions' => Transaction::where('client_id', $client->id)->count(),
            'messages' => Message::where('client_id', $client->id)->count(),
            'recherches' => Recherche::where('client_id', $client->id)->count(),
        ];
        
        // Get transaction statistics
        $transactionStats = [
            'total' => Transaction::where('client_id', $client->id)->count(),
            'enAttente' => Transaction::where('client_id', $client->id)->where('statusTransaction', 'en_attente')->count(),
            'completees' => Transaction::where('client_id', $client->id)->where('statusTransaction', 'completee')->count(),
            'annulees' => Transaction::where('client_id', $client->id)->where('statusTransaction', 'annulee')->count(),
            'montantTotal' => Transaction::where('client_id', $client->id)->where('statusTransaction', 'completee')->sum('montant'),
        ];
        
        // Get recent transactions
        $recentTransactions = Transaction::with('fournisseur.utilisateur')
            ->where('client_id', $client->id)
            ->latest('dateTransaction')
            ->limit(5)
            ->get();
        
        // Get favorites
        $favoris = Favori::with(['annonce.terreAgricole', 'annonce.fournisseur.utilisateur'])
            ->where('client_id', $client->id)
            ->latest('dateAjout')
            ->limit(5)
            ->get();
        
        // Get recent annonces
        $recentAnnonces = Annonce::with(['fournisseur.utilisateur', 'terreAgricole'])
            ->where('estActive', true)
            ->latest('dateCreation')
            ->limit(5)
            ->get();
        
        // Get unread messages
        $unreadMessages = Message::with('fournisseur.utilisateur')
            ->where('client_id', $client->id)
            ->where('lu', false)
            ->orderBy('dateHeure', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboards.client', compact(
            'stats', 
            'transactionStats', 
            'recentTransactions', 
            'favoris', 
            'recentAnnonces',
            'unreadMessages'
        ));
    }
}