<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $userType = $this->getUserType();
        
        return view('livewire.dashboard', [
            'user' => $user,
            'userType' => $userType
        ]);
    }
    
    private function getUserType()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return 'admin';
        } elseif ($user->hasRole('client')) {
            return 'client';
        } elseif ($user->hasRole('fournisseur')) {
            return 'fournisseur';
        } else {
            return 'utilisateur';
        }
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}