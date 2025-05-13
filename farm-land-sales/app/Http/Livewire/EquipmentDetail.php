<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MaterielFermierAgricole;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class EquipmentDetail extends Component
{
    public $equipmentId;
    public $equipment;
    public $message;
    
    protected $rules = [
        'message' => 'required|min:10',
    ];
    
    public function mount($id)
    {
        $this->equipmentId = $id;
        $this->equipment = MaterielFermierAgricole::with('fournisseur')->findOrFail($id);
    }
    
    public function render()
    {
        return view('livewire.equipment-detail');
    }
    
    public function sendMessage()
    {
        $this->validate();
        
        $user = Auth::user();
        $client = $user->client;
        
        $newMessage = new Message();
        $newMessage->contenu = $this->message;
        $newMessage->expediteurId = $client->id;
        $newMessage->destinataireId = $this->equipment->fournisseur->id;
        $newMessage->dateEnvoi = now();
        $newMessage->lu = false;
        $newMessage->save();
        
        // Send notification to supplier
        NotificationService::sendMessageNotification(
            $this->equipment->fournisseur->user_id,
            $client->nom . ' ' . $client->prenom
        );
        
        $this->message = '';
        
        session()->flash('message', 'Message sent successfully.');
    }
    
    public function addToFavorites()
    {
        $user = Auth::user();
        
        if (!$user->client) {
            session()->flash('error', 'Only clients can add items to favorites.');
            return;
        }
        
        $clientId = $user->client->id;
        
        // Check if already a favorite
        $exists = \App\Models\Favoris::where('client_id', $clientId)
                        ->where('equipment_id', $this->equipmentId)
                        ->exists();
                        
        if (!$exists) {
            $favorite = new \App\Models\Favoris();
            $favorite->client_id = $clientId;
            $favorite->equipment_id = $this->equipmentId;
            $favorite->type = 'equipment';
            $favorite->save();
            
            session()->flash('message', 'Added to favorites successfully.');
        } else {
            session()->flash('error', 'This item is already in your favorites.');
        }
    }
}