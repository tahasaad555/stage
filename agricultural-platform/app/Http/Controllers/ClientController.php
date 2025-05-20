<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::with('utilisateur')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'motDePasse' => 'required|string|min:8',
            'telephone' => 'nullable|string|max:20',
            'dateNaissance' => 'nullable|date',
            'typeSpecialite' => 'nullable|string|max:255',
            'preferences' => 'nullable|string|max:500',
        ]);

        // Create utilisateur
        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'motDePasse' => $request->motDePasse,
            'telephone' => $request->telephone,
            'dateNaissance' => $request->dateNaissance,
            'subscribe' => false,
        ]);

        // Create client
        $client = Client::create([
            'utilisateur_id' => $utilisateur->id,
            'typeSpecialite' => $request->typeSpecialite,
            'preferences' => $request->preferences,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client->load(['utilisateur', 'rechercheTerreAgricoles', 'favoris', 'transactions', 'paniers']);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $client->utilisateur->id,
            'telephone' => 'nullable|string|max:20',
            'dateNaissance' => 'nullable|date',
            'typeSpecialite' => 'nullable|string|max:255',
            'preferences' => 'nullable|string|max:500',
        ]);

        // Update utilisateur
        $client->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'dateNaissance' => $request->dateNaissance,
        ]);

        // Update client
        $client->update([
            'typeSpecialite' => $request->typeSpecialite,
            'preferences' => $request->preferences,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        $utilisateurId = $client->utilisateur->id;
        $client->delete();
        Utilisateur::destroy($utilisateurId);

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
    
    /**
     * Show client profile
     */
    public function profile()
    {
        $client = auth()->user()->client;
        return view('clients.profile', compact('client'));
    }
    
    /**
     * Update client profile
     */
    public function updateProfile(Request $request)
    {
        $client = auth()->user()->client;
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $client->utilisateur->id,
            'telephone' => 'nullable|string|max:20',
            'typeSpecialite' => 'nullable|string|max:255',
            'preferences' => 'nullable|string|max:500',
        ]);
        
        // Update utilisateur
        $client->utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);
        
        // Update client
        $client->update([
            'typeSpecialite' => $request->typeSpecialite,
            'preferences' => $request->preferences,
        ]);
        
        return redirect()->route('client.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}