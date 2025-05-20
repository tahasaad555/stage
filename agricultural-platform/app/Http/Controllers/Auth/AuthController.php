<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Process login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $utilisateur = Utilisateur::where('email', $credentials['email'])->first();
        
        if (!$utilisateur || !Hash::check($credentials['password'], $utilisateur->motDePasse)) {
            return back()->withErrors([
                'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
            ])->withInput();
        }
        
        Auth::login($utilisateur);
        
        // Redirect based on user type
        if ($utilisateur->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($utilisateur->isClient()) {
            return redirect()->route('client.dashboard');
        } elseif ($utilisateur->isFournisseur()) {
            return redirect()->route('fournisseur.dashboard');
        }
        
        return redirect('/');
    }

    /**
     * Show client registration form
     */
    public function showClientRegistrationForm()
    {
        return view('auth.register-client');
    }

    /**
     * Show fournisseur registration form
     */
    public function showFournisseurRegistrationForm()
    {
        return view('auth.register-fournisseur');
    }

    /**
     * Process client registration
     */
    public function registerClient(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'motDePasse' => ['required', 'confirmed', Password::min(8)],
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
        Client::create([
            'utilisateur_id' => $utilisateur->id,
            'typeSpecialite' => $request->typeSpecialite,
            'preferences' => $request->preferences,
        ]);
        
        // Login user
        Auth::login($utilisateur);
        
        return redirect()->route('client.dashboard');
    }

    /**
     * Process fournisseur registration
     */
    public function registerFournisseur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'motDePasse' => ['required', 'confirmed', Password::min(8)],
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
            'dateNaissance' => $request->dateNaissance ?? null,
            'subscribe' => false,
        ]);
        
        // Create fournisseur
        Fournisseur::create([
            'utilisateur_id' => $utilisateur->id,
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'adresseSiege' => $request->adresseSiege,
        ]);
        
        // Login user
        Auth::login($utilisateur);
        
        return redirect()->route('fournisseur.dashboard');
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}