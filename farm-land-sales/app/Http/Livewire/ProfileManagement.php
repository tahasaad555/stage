<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileManagement extends Component
{
    use WithFileUploads;
    
    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPassword_confirmation;
    
    public $nom;
    public $prenom;
    public $telephone;
    
    // Supplier-specific fields
    public $entreprise;
    public $adresse;
    public $description;
    
    public $profileImage;
    
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'profileImage' => 'nullable|image|max:1024',
        ];

        // Add supplier-specific validation rules if user is a supplier
        if (Auth::user()->fournisseur) {
            $rules['entreprise'] = 'required|string|max:255';
            $rules['adresse'] = 'required|string|max:255';
            $rules['description'] = 'required|string|min:10';
        }
        
        // Password validation rules apply only when changing password
        if ($this->currentPassword || $this->newPassword || $this->newPassword_confirmation) {
            $rules['currentPassword'] = 'required|current_password';
            $rules['newPassword'] = 'required|string|min:8|confirmed';
        }
        
        return $rules;
    }
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        
        if ($user->client) {
            $this->nom = $user->client->nom;
            $this->prenom = $user->client->prenom;
            $this->telephone = $user->client->telephone;
        } elseif ($user->fournisseur) {
            $this->nom = $user->fournisseur->nom;
            $this->prenom = $user->fournisseur->prenom;
            $this->telephone = $user->fournisseur->telephone;
            $this->entreprise = $user->fournisseur->entreprise;
            $this->adresse = $user->fournisseur->adresse;
            $this->description = $user->fournisseur->description;
        }
    }
    
    public function render()
    {
        return view('livewire.profile-management');
    }
    
    public function updateProfile()
    {
        $this->validate();
        
        $user = Auth::user();
        
        // Update user information
        $user->name = $this->name;
        $user->email = $this->email;
        
        // Update password if provided
        if ($this->newPassword) {
            $user->password = Hash::make($this->newPassword);
        }
        
        $user->save();
        
        // Update profile image if provided
        if ($this->profileImage) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $this->profileImage->store('profile-images', 'public');
            $user->profile_image = $imagePath;
            $user->save();
        }
        
        // Update client or supplier information
        if ($user->client) {
            $user->client->update([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'telephone' => $this->telephone,
            ]);
        } elseif ($user->fournisseur) {
            $user->fournisseur->update([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'telephone' => $this->telephone,
                'entreprise' => $this->entreprise,
                'adresse' => $this->adresse,
                'description' => $this->description,
            ]);
        }
        
        // Reset password fields
        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);
        
        session()->flash('message', 'Profile updated successfully!');
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}