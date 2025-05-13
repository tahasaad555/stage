<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Annonce;
use App\Models\Transaction;
use App\Models\Message;

class ClientDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $client = $user->client;
        
        $myAnnouncements = Annonce::where('clientId', $client->id)->get();
        $myTransactions = Transaction::where('clientId', $client->id)->get();
        $unreadMessages = Message::where('destinataireId', $client->id)
                                ->where('lu', false)
                                ->count();
        
        return view('livewire.client-dashboard', [
            'client' => $client,
            'myAnnouncements' => $myAnnouncements,
            'myTransactions' => $myTransactions,
            'unreadMessages' => $unreadMessages
        ]);
    }
}