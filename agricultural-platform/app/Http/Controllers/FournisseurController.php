<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the fournisseurs.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::with('utilisateur')->paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Show the form for creating a new fournisseur.
     */
    public function create()
    {
        return view('fournisseurs.create');
    }

    /**
     * Store a newly created fournisseur in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'motDePasse' => 'required|string|min:8',
            'telephone' => 'nullable|string|max:20',
            'entreprise' => 'required|string|max:255',
            'siret' => 'required|string|max:14|unique:fournisseurs',
            'adresseSiege' => 'required|string|max:255',
        ]);

        // Create utilisateur
        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'motDePasse' => $request->motDePasse,
            'telephone' => $request->telephone,
            'subscribe' => false,
        ]);

        // Create fournisseur
        $fournisseur = Fournisseur::create([
            'utilisateur_id' => $utilisateur->id,
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'adresseSiege' => $request->adresseSiege,
        ]);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Display the specified fournisseur.
     */
    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load(['utilisateur', 'annonces', 'transactions', 'avis']);
        return view('fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Show the form for editing the specified fournisseur.
     */
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified fournisseur in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $fournisseur->utilisateur->id,
            'telephone' => 'nullable|string|max:20',
            'entreprise' => 'required|string|max:255',
            'siret' => 'required|string|max:14|unique:fournisseurs,siret,' . $fournisseur->id,
            'adresseSiege' => 'required|string|max:255',
        ]);

        // Update utilisateur
        $fournisseur->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        // Update fournisseur
        $fournisseur->update([
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'adresseSiege' => $request->adresseSiege,
        ]);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified fournisseur from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $utilisateurId = $fournisseur->utilisateur->id;
        $fournisseur->delete();
        Utilisateur::destroy($utilisateurId);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
    
    /**
     * Show fournisseur profile
     */
    public function profile()
    {
        $fournisseur = auth()->user()->fournisseur;
        return view('fournisseurs.profile', compact('fournisseur'));
    }
    
    /**
     * Update fournisseur profile
     */
    public function updateProfile(Request $request)
    {
        $fournisseur = auth()->user()->fournisseur;
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $fournisseur->utilisateur->id,
            'telephone' => 'nullable|string|max:20',
            'entreprise' => 'required|string|max:255',
            'adresseSiege' => 'required|string|max:255',
        ]);
        
        // Update utilisateur
        $fournisseur->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);
        
        // Update fournisseur
        $fournisseur->update([
            'entreprise' => $request->entreprise,
            'adresseSiege' => $request->adresseSiege,
        ]);
        
        return redirect()->route('fournisseur.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}