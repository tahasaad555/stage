<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministrateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administrateurs = Administrateur::with('utilisateur')->paginate(10);
        return view('administrateurs.index', compact('administrateurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrateurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'motDePasse' => 'required|string|min:8',
            'telephone' => 'nullable|string|max:20',
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

        // Create administrateur
        $administrateur = Administrateur::create([
            'utilisateur_id' => $utilisateur->id,
        ]);

        return redirect()->route('administrateurs.index')
            ->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrateur $administrateur)
    {
        $administrateur->load('utilisateur');
        return view('administrateurs.show', compact('administrateur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateur)
    {
        return view('administrateurs.edit', compact('administrateur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrateur $administrateur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $administrateur->utilisateur->id,
            'telephone' => 'nullable|string|max:20',
        ]);

        // Update utilisateur
        $administrateur->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('administrateurs.index')
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrateur $administrateur)
    {
        $utilisateurId = $administrateur->utilisateur->id;
        $administrateur->delete();
        Utilisateur::destroy($utilisateurId);

        return redirect()->route('administrateurs.index')
            ->with('success', 'Administrateur supprimé avec succès.');
    }
}