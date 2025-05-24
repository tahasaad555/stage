<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Paiement;
use App\Models\Commission;
use App\Models\Panier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $transactions = Transaction::with(['client.utilisateur', 'fournisseur.utilisateur', 'paiement'])
                ->latest('dateTransaction')
                ->paginate(15);
        } elseif (Auth::user()->isClient()) {
            $transactions = Transaction::with(['fournisseur.utilisateur', 'paiement'])
                ->where('client_id', Auth::user()->client->id)
                ->latest('dateTransaction')
                ->paginate(15);
        } elseif (Auth::user()->isFournisseur()) {
            $transactions = Transaction::with(['client.utilisateur', 'paiement'])
                ->where('fournisseur_id', Auth::user()->fournisseur->id)
                ->latest('dateTransaction')
                ->paginate(15);
        }
        
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only clients can initiate transactions
        if (!Auth::user()->isClient()) {
            return redirect()->route('transactions.index')
                ->with('error', 'Seuls les clients peuvent initier des transactions.');
        }
        
        $panier = Panier::where('client_id', Auth::user()->client->id)
            ->where('total', '>', 0)
            ->first();
            
        if (!$panier || $panier->ligneCommandes->isEmpty()) {
            return redirect()->route('panier.show')
                ->with('error', 'Vous devez avoir des produits dans votre panier pour créer une transaction.');
        }
        
        return view('transactions.create', compact('panier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only clients can initiate transactions
        if (!Auth::user()->isClient()) {
            return redirect()->route('transactions.index')
                ->with('error', 'Seuls les clients peuvent initier des transactions.');
        }
        
        $request->validate([
            'panier_id' => 'required|exists:paniers,id',
            'methodePaiement' => 'required|string|in:carte,virement,paypal',
        ]);
        
        $panier = Panier::findOrFail($request->panier_id);
        
        // Ensure the panier belongs to the current user
        if ($panier->client_id != Auth::user()->client->id) {
            return redirect()->route('transactions.index')
                ->with('error', 'Ce panier ne vous appartient pas.');
        }
        
        // Determine the fournisseur from the first item in the panier
        // (This is a simplification - in a real app, you would need to handle multiple fournisseurs)
        $ligneCommande = $panier->ligneCommandes->first();
        $fournisseurId = null;
        
        if ($ligneCommande->terre_id) {
            $fournisseurId = $ligneCommande->terre->annonce->fournisseur_id;
        } elseif ($ligneCommande->produit_id) {
            // Assuming produits are linked to fournisseurs in your model
            // You would need to adapt this based on your actual data structure
            $fournisseurId = 1; // Placeholder - replace with actual logic
        }
        
        if (!$fournisseurId) {
            return redirect()->route('transactions.index')
                ->with('error', 'Impossible de déterminer le fournisseur pour cette transaction.');
        }
        
        // Calculate commission (e.g., 5% of total)
        $commission = $panier->total * 0.05;
        
        // Create transaction
        $transaction = Transaction::create([
            'client_id' => Auth::user()->client->id,
            'fournisseur_id' => $fournisseurId,
            'dateTransaction' => now(),
            'montant' => $panier->total,
            'commission' => $commission,
            'methodePaiement' => $request->methodePaiement,
            'statusTransaction' => 'en_attente',
            'estVerifiee' => false,
            'referencePaiement' => 'REF-' . uniqid(),
        ]);
        
        // Create payment
        Paiement::create([
            'transaction_id' => $transaction->id,
            'methode' => $request->methodePaiement,
            'details' => 'Paiement pour la transaction #' . $transaction->id,
            'status' => 'en_attente',
            'valide' => false,
            'annule' => false,
        ]);
        
        // Create commission
        Commission::create([
            'transaction_id' => $transaction->id,
            'taux' => 5, // 5%
            'montant' => $commission,
            'datePrelevement' => now()->addDays(3), // Example: commission is taken 3 days after transaction
            'calcule' => $commission,
        ]);
        
        // Mark the panier as processed (in a real app, you might set a status field)
        $panier->update([
            'dateCreation' => now(),
        ]);
        
        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction créée avec succès. Veuillez procéder au paiement.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Ensure the user has permission to view this transaction
        if (!Auth::user()->isAdmin() && 
            !(Auth::user()->isClient() && $transaction->client_id == Auth::user()->client->id) &&
            !(Auth::user()->isFournisseur() && $transaction->fournisseur_id == Auth::user()->fournisseur->id)) {
            abort(403, 'Unauthorized action.');
        }
        
        $transaction->load(['client.utilisateur', 'fournisseur.utilisateur', 'paiement', 'commission']);
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Process payment for a transaction.
     */
   public function processPayment(Request $request, Transaction $transaction)
{
    // Ensure the user is the client who created this transaction
    if (!Auth::user()->isClient() || $transaction->client_id != Auth::user()->client->id) {
        abort(403, 'Unauthorized action.');
    }
    
    // Check if transaction is already paid
    if ($transaction->statusTransaction !== 'en_attente') {
        return redirect()->route('transactions.show', $transaction)
            ->with('error', 'Cette transaction a déjà été traitée.');
    }
    
    // Mock payment gateway integration
    // In a real app, you would integrate with a payment gateway like Stripe, PayPal etc.
    try {
        // Process payment with gateway
        $paymentSuccessful = true; // Assume success for now
        
        if ($paymentSuccessful) {
            // Update payment status
            $transaction->paiement->update([
                'status' => 'completee',
                'valide' => true,
            ]);
            
            // Update transaction status
            $transaction->update([
                'statusTransaction' => 'completee',
                'estVerifiee' => true,
            ]);
            
            // Update property status if it's a land purchase
            foreach ($transaction->panier->ligneCommandes as $ligne) {
                if ($ligne->terre_id) {
                    $ligne->terre->update(['status' => 'vendu']);
                    
                    // Update the related announcement
                    if ($ligne->terre->annonce) {
                        $ligne->terre->annonce->update(['estActive' => false]);
                    }
                }
            }
            
            // Create notification for both client and supplier
            $this->createTransactionNotifications($transaction);
            
            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Paiement traité avec succès.');
        } else {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Échec du traitement du paiement. Veuillez réessayer.');
        }
    } catch (\Exception $e) {
        return redirect()->route('transactions.show', $transaction)
            ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
    }
}

private function createTransactionNotifications($transaction)
{
    // Notify client
    Notification::create([
        'utilisateur_id' => $transaction->client->utilisateur->id,
        'titre' => 'Transaction complétée',
        'contenu' => "Votre transaction #" . $transaction->id . " a été complétée avec succès.",
        'lue' => false,
        'dateCreation' => now(),
    ]);
    
    // Notify supplier
    Notification::create([
        'utilisateur_id' => $transaction->fournisseur->utilisateur->id,
        'titre' => 'Nouvelle vente',
        'contenu' => "Une transaction #" . $transaction->id . " a été complétée.",
        'lue' => false,
        'dateCreation' => now(),
    ]);
}

    /**
     * Cancel a transaction.
     */
    public function cancel(Transaction $transaction)
    {
        // Ensure the user has permission to cancel this transaction
        if (!Auth::user()->isAdmin() && 
            !(Auth::user()->isClient() && $transaction->client_id == Auth::user()->client->id) &&
            !(Auth::user()->isFournisseur() && $transaction->fournisseur_id == Auth::user()->fournisseur->id)) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if transaction can be canceled
        if ($transaction->statusTransaction !== 'en_attente') {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Seules les transactions en attente peuvent être annulées.');
        }
        
        // Update transaction and payment status
        $transaction->update(['statusTransaction' => 'annulee']);
        
        if ($transaction->paiement) {
            $transaction->paiement->update([
                'status' => 'annulee',
                'annule' => true,
            ]);
        }
        
        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction annulée avec succès.');
    }
}