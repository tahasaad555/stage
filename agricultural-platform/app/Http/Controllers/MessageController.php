<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the messages.
     */
    public function index()
    {
        if (Auth::user()->isClient()) {
            $messages = Message::where('client_id', Auth::user()->client->id)
                ->with('fournisseur.utilisateur')
                ->latest('dateHeure')
                ->paginate(20);
                
            $conversation_partners = Message::where('client_id', Auth::user()->client->id)
                ->select('fournisseur_id')
                ->distinct()
                ->with('fournisseur.utilisateur')
                ->get()
                ->pluck('fournisseur');
                
            return view('messages.index-client', compact('messages', 'conversation_partners'));
                
        } elseif (Auth::user()->isFournisseur()) {
            $messages = Message::where('fournisseur_id', Auth::user()->fournisseur->id)
                ->with('client.utilisateur')
                ->latest('dateHeure')
                ->paginate(20);
                
            $conversation_partners = Message::where('fournisseur_id', Auth::user()->fournisseur->id)
                ->select('client_id')
                ->distinct()
                ->with('client.utilisateur')
                ->get()
                ->pluck('client');
                
            return view('messages.index-fournisseur', compact('messages', 'conversation_partners'));
        }
        
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }
    
    /**
     * Display conversation with a specific user
     */
    public function conversation($userId)
    {
        if (Auth::user()->isClient()) {
            $fournisseur = \App\Models\Fournisseur::findOrFail($userId);
            
            $messages = Message::where(function($query) use ($fournisseur) {
                $query->where('client_id', Auth::user()->client->id)
                      ->where('fournisseur_id', $fournisseur->id);
            })
            ->orderBy('dateHeure')
            ->get();
            
            // Mark messages as read
            foreach ($messages as $message) {
                if (!$message->lu && $message->fournisseur_id == $fournisseur->id) {
                    $message->update(['lu' => true]);
                }
            }
            
            return view('messages.conversation', compact('messages', 'fournisseur'));
            
        } elseif (Auth::user()->isFournisseur()) {
            $client = \App\Models\Client::findOrFail($userId);
            
            $messages = Message::where(function($query) use ($client) {
                $query->where('fournisseur_id', Auth::user()->fournisseur->id)
                      ->where('client_id', $client->id);
            })
            ->orderBy('dateHeure')
            ->get();
            
            // Mark messages as read
            foreach ($messages as $message) {
                if (!$message->lu && $message->client_id == $client->id) {
                    $message->update(['lu' => true]);
                }
            }
            
            return view('messages.conversation', compact('messages', 'client'));
        }
        
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }
    
    /**
     * Send a message
     */
    public function send(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
            'recipient_id' => 'required|integer',
        ]);
        
        if (Auth::user()->isClient()) {
            $message = Message::create([
                'client_id' => Auth::user()->client->id,
                'fournisseur_id' => $request->recipient_id,
                'contenu' => $request->contenu,
                'dateHeure' => now(),
                'lu' => false,
            ]);
            
            return redirect()->back()->with('success', 'Message envoyé avec succès.');
            
        } elseif (Auth::user()->isFournisseur()) {
            $message = Message::create([
                'fournisseur_id' => Auth::user()->fournisseur->id,
                'client_id' => $request->recipient_id,
                'contenu' => $request->contenu,
                'dateHeure' => now(),
                'lu' => false,
            ]);
            
            return redirect()->back()->with('success', 'Message envoyé avec succès.');
        }
        
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à envoyer un message.');
    }
    
    /**
     * Mark all messages as read
     */
    public function markAllAsRead()
    {
        if (Auth::user()->isClient()) {
            Message::where('client_id', Auth::user()->client->id)
                  ->where('lu', false)
                  ->update(['lu' => true]);
                  
        } elseif (Auth::user()->isFournisseur()) {
            Message::where('fournisseur_id', Auth::user()->fournisseur->id)
                  ->where('lu', false)
                  ->update(['lu' => true]);
        }
        
        return redirect()->back()->with('success', 'Tous les messages ont été marqués comme lus.');
    }
}