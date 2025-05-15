<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessagesSent extends Component
{
    use WithPagination;
    
    public $search = '';
    
    public function render()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        $query = Message::where('expediteurId', $userId);
        
        if ($this->search) {
            $query->where('contenu', 'like', '%' . $this->search . '%')
                  ->orWhereHas('destinataire', function($q) {
                      $q->where('nom', 'like', '%' . $this->search . '%');
                  });
        }
        
        $messages = $query->orderBy('dateEnvoi', 'desc')
                        ->paginate(10);
        
        return view('livewire.messages-sent', [
            'messages' => $messages
        ]);
    }
    
    public function viewMessage($messageId)
    {
        return redirect()->route('messages.inbox', ['messageId' => $messageId]);
    }
    
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        
        // Verify ownership
        $user = Auth::user();
        $userId = $user->id;
        if ($message->expediteurId != $userId) {
            session()->flash('error', 'You do not have permission to delete this message.');
            return;
        }
        
        $message->delete();
        
        session()->flash('message', 'Message deleted successfully.');
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
}