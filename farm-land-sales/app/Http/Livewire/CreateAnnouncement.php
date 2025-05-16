<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;

class CreateAnnouncement extends Component
{
    use WithFileUploads;
    
    public $titre;
    public $type;
    public $description;
    public $prix;
    public $image;
    public $estActif = false;
    
    protected $rules = [
        'titre' => 'required|min:5',
        'type' => 'required|in:land,equipment',
        'description' => 'required|min:20',
        'prix' => 'required|numeric',
        'image' => 'required|image|max:1024',
    ];
    
    public function render()
    {
        return view('livewire.create-announcement');
    }
    
    public function submit()
    {
        $this->validate();
        
        $imagePath = $this->image->store('announcements', 'public');
        
        $user = Auth::user();
        $client = $user->client;
        
        $announcement = new Annonce();
        $announcement->titre = $this->titre;
        $announcement->type = $this->type;
        $announcement->description = $this->description;
        $announcement->prix = $this->prix;
        $announcement->image = $imagePath;
        $announcement->clientId = $client->id;
        $announcement->dateCreation = now();
        $announcement->estActif = $this->estActif;
        $announcement->save();
        
        session()->flash('message', 'Announcement created successfully!');
        
        $this->reset(['titre', 'type', 'description', 'prix', 'image']);
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}