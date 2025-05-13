<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TerrainAgricole;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class LandDetail extends Component
{
    public $landId;
    public $land;
    public $message;
    
    protected $rules = [
        'message' => 'required|min:10',
    ];
    
    public function mount($id)
    {
        $this->landId = $id;
        $this->land = TerrainAgricole::findOrFail($id);
    }
    
    public function render()
    {
        return view('livewire.land-detail');
    }
    
    public function sendMessage()
    {
        $this->validate();
        
        $user = Auth::user();
        $client = $user->client;
        
        $newMessage = new Message();
        $newMessage->contenu = $this->message;
        $newMessage->expediteurId = $client->id;
        $newMessage->destinataireId = $this->land->proprietaireId;
        $newMessage->dateEnvoi = now();
        $newMessage->lu = false;
        $newMessage->save();
        
        $this->message = '';
        
        session()->flash('message', 'Message sent successfully.');
    }
    
    public function addToFavorites()
    {
        $user = Auth::user();
        $client = $user->client;
        
        // Logic to add land to favorites
        
        session()->flash('message', 'Added to favorites.');
    }
}