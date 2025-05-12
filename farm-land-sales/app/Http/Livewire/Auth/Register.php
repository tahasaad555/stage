<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $userType = 'client';
    
    public $nom;
    public $prenom;
    public $telephone;
    
    // For suppliers only
    public $entreprise;
    public $adresse;
    public $description;
    
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'userType' => 'required|in:client,fournisseur',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ];
        
        if ($this->userType === 'fournisseur') {
            $rules['entreprise'] = 'required|string|max:255';
            $rules['adresse'] = 'required|string|max:255';
            $rules['description'] = 'required|string|min:10';
        }
        
        return $rules;
    }
    
    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.guest');
    }
    
    public function register()
    {
        $this->validate();
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        if ($this->userType === 'client') {
            Client::create([
                'user_id' => $user->id,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'telephone' => $this->telephone,
            ]);
        } else if ($this->userType === 'fournisseur') {
            Fournisseur::create([
                'user_id' => $user->id,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'entreprise' => $this->entreprise,
                'telephone' => $this->telephone,
                'adresse' => $this->adresse,
                'description' => $this->description,
            ]);
        }
        
        event(new Registered($user));
        
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }
}