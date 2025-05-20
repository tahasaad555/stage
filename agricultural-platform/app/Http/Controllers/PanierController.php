<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\LigneCommande;
use App\Models\TerreAgricole;
use App\Models\ProduitAgricole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    /**
     * Display the user's cart
     */
    public function show()
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour accéder à votre panier.');
        }
        
        $client = Auth::user()->client;
        
        // Get or create panier
        $panier = Panier::firstOrCreate(
            ['client_id' => $client->id, 'total' => 0],
            ['dateCreation' => now()]
        );
        
        $panier->load(['ligneCommandes.produit', 'ligneCommandes.terre']);
        
        return view('panier.show', compact('panier'));
    }
    
    /**
     * Add a product to the cart
     */
    public function addProduit(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour ajouter des produits au panier.');
        }
        
        $request->validate([
            'produit_id' => 'required|exists:produits_agricoles,id',
            'quantite' => 'required|integer|min:1',
        ]);
        
        $client = Auth::user()->client;
        $produit = ProduitAgricole::findOrFail($request->produit_id);
        
        // Check if product is available in the requested quantity
        if ($produit->quantite < $request->quantite) {
            return redirect()->back()
                ->with('error', 'Quantité demandée non disponible.');
        }
        
        // Get or create panier
        $panier = Panier::firstOrCreate(
            ['client_id' => $client->id, 'total' => 0],
            ['dateCreation' => now()]
        );
        
        // Check if the product is already in the cart
        $ligneCommande = $panier->ligneCommandes()
            ->where('produit_id', $produit->id)
            ->first();
            
        if ($ligneCommande) {
            // Update existing line
            $ligneCommande->quantite += $request->quantite;
            $ligneCommande->save();
        } else {
            // Create new line
            $panier->ligneCommandes()->create([
                'produit_id' => $produit->id,
                'quantite' => $request->quantite,
                'prixUnitaire' => $produit->prix,
            ]);
        }
        
        // Update panier total
        $this->updatePanierTotal($panier);
        
        return redirect()->route('panier.show')
            ->with('success', 'Produit ajouté au panier.');
    }
    
    /**
     * Add a terre agricole to the cart
     */
    public function addTerre(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour ajouter des terres au panier.');
        }
        
        $request->validate([
            'terre_id' => 'required|exists:terres_agricoles,id',
        ]);
        
        $client = Auth::user()->client;
        $terre = TerreAgricole::findOrFail($request->terre_id);
        
        // Check if terre is available
        if ($terre->status !== 'disponible') {
            return redirect()->back()
                ->with('error', 'Cette terre n\'est plus disponible.');
        }
        
        // Get or create panier
        $panier = Panier::firstOrCreate(
            ['client_id' => $client->id, 'total' => 0],
            ['dateCreation' => now()]
        );
        
        // Check if the terre is already in the cart
        $ligneCommande = $panier->ligneCommandes()
            ->where('terre_id', $terre->id)
            ->first();
            
        if ($ligneCommande) {
            return redirect()->back()
                ->with('error', 'Cette terre est déjà dans votre panier.');
        } else {
            // Create new line
            $panier->ligneCommandes()->create([
                'terre_id' => $terre->id,
                'quantite' => 1, // A terre can only be bought once
                'prixUnitaire' => $terre->prix,
            ]);
        }
        
        // Update panier total
        $this->updatePanierTotal($panier);
        
        return redirect()->route('panier.show')
            ->with('success', 'Terre agricole ajoutée au panier.');
    }
    
    /**
     * Update item quantity in cart
     */
    public function updateQuantity(Request $request, LigneCommande $ligneCommande)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour modifier votre panier.');
        }
        
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);
        
        $client = Auth::user()->client;
        $panier = $ligneCommande->panier;
        
        // Ensure the ligne_commande belongs to the user's panier
        if ($panier->client_id !== $client->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // If it's a product, check availability
        if ($ligneCommande->produit_id) {
            $produit = $ligneCommande->produit;
            if ($produit->quantite < $request->quantite) {
                return redirect()->back()
                    ->with('error', 'Quantité demandée non disponible.');
            }
        } elseif ($ligneCommande->terre_id) {
            // A terre can only have a quantity of 1
            if ($request->quantite > 1) {
                return redirect()->back()
                    ->with('error', 'Vous ne pouvez acheter qu\'une seule fois cette terre agricole.');
            }
        }
        
        // Update quantity
        $ligneCommande->quantite = $request->quantite;
        $ligneCommande->save();
        
        // Update panier total
        $this->updatePanierTotal($panier);
        
        return redirect()->route('panier.show')
            ->with('success', 'Quantité mise à jour.');
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem(LigneCommande $ligneCommande)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour modifier votre panier.');
        }
        
        $client = Auth::user()->client;
        $panier = $ligneCommande->panier;
        
        // Ensure the ligne_commande belongs to the user's panier
        if ($panier->client_id !== $client->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Remove the item
        $ligneCommande->delete();
        
        // Update panier total
        $this->updatePanierTotal($panier);
        
        return redirect()->route('panier.show')
            ->with('success', 'Article retiré du panier.');
    }
    
    /**
     * Empty the cart
     */
    public function emptyCart()
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour modifier votre panier.');
        }
        
        $client = Auth::user()->client;
        $panier = $client->paniers()->where('total', '>', 0)->first();
        
        if ($panier) {
            // Delete all ligne_commandes
            $panier->ligneCommandes()->delete();
            
            // Update panier total
            $panier->total = 0;
            $panier->save();
        }
        
        return redirect()->route('panier.show')
            ->with('success', 'Panier vidé avec succès.');
    }
    
    /**
     * Helper method to update panier total
     */
    private function updatePanierTotal(Panier $panier)
    {
        $total = 0;
        
        foreach ($panier->ligneCommandes as $ligne) {
            $total += $ligne->quantite * $ligne->prixUnitaire;
        }
        
        $panier->total = $total;
        $panier->save();
    }
}
