<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;
    
    public $search = '';
    public $userType = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;
    
    public $showModal = false;
    public $isEditing = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|in:client,fournisseur,admin',
    ];
    
    public function render()
    {
        $query = User::query();
        
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                 ->orWhere('email', 'like', '%' . $this->search . '%');
        }
        
        if ($this->userType !== 'all') {
            if ($this->userType === 'client') {
                $query->has('client');
            } elseif ($this->userType === 'fournisseur') {
                $query->has('fournisseur');
            } elseif ($this->userType === 'admin') {
                $query->has('administrateur');
            } elseif ($this->userType === 'unassigned') {
                $query->doesntHave('client')
                      ->doesntHave('fournisseur')
                      ->doesntHave('administrateur');
            }
        }
        
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $users = $query->paginate(10);
        
        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function openModal($userId = null)
    {
        $this->resetValidation();
        
        if ($userId) {
            $this->isEditing = true;
            $this->userId = $userId;
            
            $user = User::findOrFail($userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = '';
            
            if ($user->administrateur) {
                $this->role = 'admin';
            } elseif ($user->client) {
                $this->role = 'client';
            } elseif ($user->fournisseur) {
                $this->role = 'fournisseur';
            } else {
                $this->role = '';
            }
            
            // Update validation rules for editing
            $this->rules['email'] = 'required|string|email|max:255|unique:users,email,' . $userId;
            $this->rules['password'] = 'nullable|string|min:8';
        } else {
            $this->isEditing = false;
            $this->userId = null;
            $this->name = '';
            $this->email = '';
            $this->password = '';
            $this->role = 'client';
            
            // Reset validation rules for new user
            $this->rules['email'] = 'required|string|email|max:255|unique:users';
            $this->rules['password'] = 'required|string|min:8';
        }
        
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function saveUser()
    {
        $this->validate();
        
        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            
            $user->save();
            
            // Update user role if changed
            $oldRole = '';
            if ($user->administrateur) {
                $oldRole = 'admin';
            } elseif ($user->client) {
                $oldRole = 'client';
            } elseif ($user->fournisseur) {
                $oldRole = 'fournisseur';
            }
            
            if ($oldRole !== $this->role) {
                // Remove old role
                if ($oldRole === 'admin') {
                    $user->administrateur()->delete();
                } elseif ($oldRole === 'client') {
                    $user->client()->delete();
                } elseif ($oldRole === 'fournisseur') {
                    $user->fournisseur()->delete();
                }
                
                // Add new role
                $this->assignRole($user, $this->role);
            }
            
            session()->flash('message', 'User updated successfully.');
        } else {
            // Create new user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            
            // Assign role
            $this->assignRole($user, $this->role);
            
            session()->flash('message', 'User created successfully.');
        }
        
        $this->closeModal();
    }
    
    private function assignRole($user, $role)
    {
        if ($role === 'admin') {
            Administrateur::create([
                'user_id' => $user->id
            ]);
        } elseif ($role === 'client') {
            Client::create([
                'user_id' => $user->id,
                'nom' => $user->name,
                'prenom' => '',
                'telephone' => ''
            ]);
        } elseif ($role === 'fournisseur') {
            Fournisseur::create([
                'user_id' => $user->id,
                'nom' => $user->name,
                'prenom' => '',
                'entreprise' => '',
                'telephone' => '',
                'adresse' => '',
                'description' => ''
            ]);
        }
    }
    
    public function deleteUser($userId)
    {
        User::findOrFail($userId)->delete();
        
        session()->flash('message', 'User deleted successfully.');
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingUserType()
    {
        $this->resetPage();
    }
}