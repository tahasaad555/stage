<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TerreAgricole;
use App\Models\HistoriqueAnnonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annonces = Annonce::with(['fournisseur.utilisateur', 'terreAgricole'])
            ->where('estActive', true)
            ->latest('dateCreation')
            ->paginate(12);
            
        return view('annonces.index', compact('annonces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Annonce::class);
        
        // Get terres agricoles without annonces
        $terresDisponibles = TerreAgricole::whereDoesntHave('annonce')
            ->where('status', 'disponible')
            ->get();
        
        return view('annonces.create', compact('terresDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Annonce::class);
        
        $request->validate([
            'terre_agricole_id' => 'required|exists:terres_agricoles,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        // Create annonce
        $annonce = Annonce::create([
            'fournisseur_id' => auth()->user()->fournisseur->id,
            'terre_agricole_id' => $request->terre_agricole_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'dateCreation' => now(),
            'estActive' => true,
        ]);
        
        // Create historique
        HistoriqueAnnonce::create([
            'annonce_id' => $annonce->id,
            'dateModification' => now(),
            'modification' => 'Création',
            'description' => 'Annonce créée',
        ]);
        
        return redirect()->route('annonces.index')
            ->with('success', 'Annonce créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Annonce $annonce)
    {
        $annonce->load(['fournisseur.utilisateur', 'terreAgricole', 'historiqueAnnonces', 'favoris']);
        return view('annonces.show', compact('annonce'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Annonce $annonce)
    {
        $this->authorize('update', $annonce);
        
        return view('annonces.edit', compact('annonce'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Annonce $annonce)
    {
        $this->authorize('update', $annonce);
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'estActive' => 'boolean',
        ]);
        
        // Update annonce
        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'estActive' => $request->has('estActive') ? $request->estActive : $annonce->estActive,
        ]);
        
        // Create historique
        HistoriqueAnnonce::create([
            'annonce_id' => $annonce->id,
            'dateModification' => now(),
            'modification' => 'Mise à jour',
            'description' => 'Annonce mise à jour',
        ]);
        
        return redirect()->route('annonces.show', $annonce)
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Annonce $annonce)
    {
        $this->authorize('delete', $annonce);
        
        // Create historique before deleting
        HistoriqueAnnonce::create([
            'annonce_id' => $annonce->id,
            'dateModification' => now(),
            'modification' => 'Suppression',
            'description' => 'Annonce supprimée',
        ]);
        
        // Delete the annonce
        $annonce->delete();
        
        return redirect()->route('annonces.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }
    
    /**
     * Toggle favorite status for the current user
     */
    public function toggleFavorite(Annonce $annonce)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que client pour ajouter des favoris.');
        }
        
        $client = Auth::user()->client;
        $favori = $client->favoris()->where('annonce_id', $annonce->id)->first();
        
        if ($favori) {
            // If already a favorite, remove it
            $favori->delete();
            $message = 'Annonce retirée des favoris.';
        } else {
            // Add to favorites
            $client->favoris()->create([
                'annonce_id' => $annonce->id,
                'dateAjout' => now(),
            ]);
            $message = 'Annonce ajoutée aux favoris.';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Get annonces by fournisseur
     */
    public function mesAnnonces()
    {
        if (!Auth::check() || !Auth::user()->isFournisseur()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté en tant que fournisseur pour voir vos annonces.');
        }
        
        $fournisseur = Auth::user()->fournisseur;
        $annonces = $fournisseur->annonces()
            ->with('terreAgricole')
            ->latest('dateCreation')
            ->paginate(10);
            
        return view('annonces.mes-annonces', compact('annonces'));
    }
}