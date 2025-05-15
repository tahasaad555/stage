<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\TerrainAgricole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyLandsManagement extends Component
{
    use WithPagination, WithFileUploads;
    
    public $titre;
    public $description;
    public $adresse;
    public $superficie;
    public $prix;
    public $region;
    public $coordonneesGPS;
    public $images;
    public $type;
    
    public $landId;
    public $isEditing = false;
    
    protected $rules = [
        'titre' => 'required|string|min:3',
        'description' => 'required|string|min:10',
        'adresse' => 'required|string',
        'superficie' => 'required|numeric|min:0',
        'prix' => 'required|numeric|min:0',
        'region' => 'nullable|string',
        'coordonneesGPS' => 'nullable|string',
        'images' => 'nullable|image|max:1024',
        'type' => 'nullable|string',
    ];
    
    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client->id;
        
        $lands = TerrainAgricole::where('proprietaireId', $clientId)
                             ->latest()
                             ->paginate(10);
        
        return view('livewire.my-lands-management', [
            'lands' => $lands
        ]);
    }
    
    public function saveLand()
    {
        if ($this->isEditing) {
            $this->rules['images'] = 'nullable|image|max:1024';
        } else {
            $this->rules['images'] = 'required|image|max:1024';
        }
        
        $this->validate();
        
        $user = Auth::user();
        $clientId = $user->client->id;
        
        if ($this->isEditing) {
            $land = TerrainAgricole::findOrFail($this->landId);
            // Verify ownership
            if ($land->proprietaireId != $clientId) {
                session()->flash('error', 'You do not have permission to edit this land.');
                return;
            }
        } else {
            $land = new TerrainAgricole();
            $land->proprietaireId = $clientId;
            $land->statut = 'available';
        }
        
        $land->titre = $this->titre;
        $land->description = $this->description;
        $land->adresse = $this->adresse;
        $land->superficie = $this->superficie;
        $land->prix = $this->prix;
        $land->region = $this->region;
        $land->coordonneesGPS = $this->coordonneesGPS;
        $land->type = $this->type;
        
        if ($this->images) {
            // Delete old image if updating
            if ($this->isEditing && $land->images) {
                Storage::disk('public')->delete($land->images);
            }
            
            $imagePath = $this->images->store('lands', 'public');
            $land->images = $imagePath;
        }
        
        $land->save();
        
        $this->reset(['titre', 'description', 'adresse', 'superficie', 'prix', 'region', 'coordonneesGPS', 'images', 'type', 'isEditing', 'landId']);
        
        session()->flash('message', $this->isEditing ? 'Land updated successfully!' : 'Land added successfully!');
    }
    
    public function editLand($id)
    {
        $this->isEditing = true;
        $this->landId = $id;
        
        $land = TerrainAgricole::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $clientId = $user->client->id;
        if ($land->proprietaireId != $clientId) {
            session()->flash('error', 'You do not have permission to edit this land.');
            return;
        }
        
        $this->titre = $land->titre;
        $this->description = $land->description;
        $this->adresse = $land->adresse;
        $this->superficie = $land->superficie;
        $this->prix = $land->prix;
        $this->region = $land->region;
        $this->coordonneesGPS = $land->coordonneesGPS;
        $this->type = $land->type;
    }
    
    public function cancelEdit()
    {
        $this->reset(['titre', 'description', 'adresse', 'superficie', 'prix', 'region', 'coordonneesGPS', 'images', 'type', 'isEditing', 'landId']);
    }
    
    public function deleteLand($id)
    {
        $land = TerrainAgricole::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $clientId = $user->client->id;
        if ($land->proprietaireId != $clientId) {
            session()->flash('error', 'You do not have permission to delete this land.');
            return;
        }
        
        // Check if there are any pending transactions
        $hasPendingTransactions = $land->transactions()->where('statut', '!=', 'Cancelled')->exists();
        
        if ($hasPendingTransactions) {
            session()->flash('error', 'Cannot delete land with pending transactions.');
            return;
        }
        
        // Delete the image file
        if ($land->images) {
            Storage::disk('public')->delete($land->images);
        }
        
        $land->delete();
        
        session()->flash('message', 'Land deleted successfully!');
    }
}