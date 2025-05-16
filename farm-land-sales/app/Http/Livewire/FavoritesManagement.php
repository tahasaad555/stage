<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Favoris;
use App\Models\TerrainAgricole;
use App\Models\MaterielFermierAgricole;
use Illuminate\Support\Facades\Auth;

class FavoritesManagement extends Component
{
    public $type = 'lands'; // Default view is lands
    
    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client->id;
        
        if ($this->type === 'lands') {
            $favorites = Favoris::where('client_id', $clientId)
                ->where('type', 'terrain')
                ->with('terrain')
                ->get()
                ->pluck('terrain');
                
            return view('livewire.favorites-management', [
                'favorites' => $favorites,
                'type' => $this->type
            ]);
        } else {
            $favorites = Favoris::where('client_id', $clientId)
                ->where('type', 'equipment')
                ->with('equipment')
                ->get()
                ->pluck('equipment');
                
            return view('livewire.favorites-management', [
                'favorites' => $favorites,
                'type' => $this->type
            ]);
        }
    }
    
    public function switchType($type)
    {
        if (in_array($type, ['lands', 'equipment'])) {
            $this->type = $type;
        }
    }
    
    public function removeFavorite($id, $type)
    {
        $user = Auth::user();
        $clientId = $user->client->id;
        
        Favoris::where('client_id', $clientId)
              ->where($type === 'lands' ? 'terrain_id' : 'equipment_id', $id)
              ->delete();
              
        session()->flash('message', 'Item removed from favorites.');
    }
    
    public function addToFavorites($id, $type)
    {
        $user = Auth::user();
        $clientId = $user->client->id;
        
        // Check if already a favorite
        $exists = Favoris::where('client_id', $clientId)
                        ->where($type === 'terrain' ? 'terrain_id' : 'equipment_id', $id)
                        ->exists();
                        
        if (!$exists) {
            $favorite = new Favoris();
            $favorite->client_id = $clientId;
            $favorite->type = $type;
            
            if ($type === 'terrain') {
                $favorite->terrain_id = $id;
            } else {
                $favorite->equipment_id = $id;
            }
            
            $favorite->save();
            
            session()->flash('message', 'Added to favorites successfully.');
        } else {
            session()->flash('error', 'Item is already in your favorites.');
        }
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}