<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessagesInbox extends Component
{
    public $messages;
    public $selectedMessage;
    public $replyContent;
    
    protected $rules = [
        'replyContent' => 'required|min:3'
    ];
    
    public function mount()
    {
        $this->loadMessages();
    }
    
    public function loadMessages()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        $this->messages = Message::where('destinataireId', $userId)
                               ->orderBy('dateEnvoi', 'desc')
                               ->get();
    }
    
    public function render()
    {
        return view('livewire.messages-inbox');
    }
    
    public function viewMessage($messageId)
    {
        $this->selectedMessage = Message::findOrFail($messageId);
        
        if (!$this->selectedMessage->lu) {
            $this->selectedMessage->lu = true;
            $this->selectedMessage->save();
        }
    }
    
    public function sendReply()
    {
        $this->validate();
        
        $user = Auth::user();
        $userId = $user->id;
        
        $reply = new Message();
        $reply->contenu = $this->replyContent;
        $reply->expediteurId = $userId;
        $reply->destinataireId = $this->selectedMessage->expediteurId;
        $reply->dateEnvoi = now();
        $reply->lu = false;
        $reply->save();
        
        $this->replyContent = '';
        
        session()->flash('message', 'Reply sent successfully.');
    }
}