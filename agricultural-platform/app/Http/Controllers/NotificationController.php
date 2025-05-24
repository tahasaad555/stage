<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('utilisateur_id', Auth::id())
            ->orderBy('dateCreation', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->utilisateur_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $notification->marquerCommeLue();
        
        return redirect()->back()
            ->with('success', 'Notification marquée comme lue.');
    }
    
    public function markAllAsRead()
    {
        Notification::where('utilisateur_id', Auth::id())
            ->where('lue', false)
            ->update(['lue' => true]);
            
        return redirect()->back()
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}