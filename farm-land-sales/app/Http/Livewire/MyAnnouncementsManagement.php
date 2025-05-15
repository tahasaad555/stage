<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyAnnouncementsManagement extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = '';
    public $type = '';
    
    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client->id;
        
        $query = Annonce::where('clientId', $clientId);
        
        if ($this->search) {
            $query->where('titre', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }
        
        if ($this->status) {
            $query->where('estActif', $this->status === 'active');
        }
        
        if ($this->type) {
            $query->where('type', $this->type);
        }
        
        $announcements = $query->latest()
                             ->paginate(10);
        
        return view('livewire.my-announcements-management', [
            'announcements' => $announcements
        ]);
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->type = '';
    }
    
    public function deleteAnnouncement($id)
    {
        $announcement = Annonce::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $clientId = $user->client->id;
        if ($announcement->clientId != $clientId) {
            session()->flash('error', 'You do not have permission to delete this announcement.');
            return;
        }
        
        // Check if there are any pending transactions
        $hasPendingTransactions = $announcement->transactions()
                                              ->where('statut', '!=', 'Cancelled')
                                              ->exists();
        
        if ($hasPendingTransactions) {
            session()->flash('error', 'Cannot delete announcement with pending transactions.');
            return;
        }
        
        // Delete the image file
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
        
        $announcement->delete();
        
        session()->flash('message', 'Announcement deleted successfully!');
    }
    
    public function toggleStatus($id)
    {
        $announcement = Annonce::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        $clientId = $user->client->id;
        if ($announcement->clientId != $clientId) {
            session()->flash('error', 'You do not have permission to modify this announcement.');
            return;
        }
        
        $announcement->estActif = !$announcement->estActif;
        $announcement->save();
        
        $status = $announcement->estActif ? 'activated' : 'deactivated';
        session()->flash('message', "Announcement {$status} successfully!");
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }
}