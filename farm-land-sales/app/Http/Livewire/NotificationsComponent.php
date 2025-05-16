<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationsComponent extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
                                     ->orderBy('dateCreation', 'desc')
                                     ->get();
                                     
        $unreadCount = $notifications->where('lu', false)->count();
        
        return view('livewire.notifications-component', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->lu = true;
        $notification->save();
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
                   ->where('lu', false)
                   ->update(['lu' => true]);
                   
        session()->flash('message', 'All notifications marked as read.');
    }
    
    public function deleteNotification($id)
    {
        Notification::findOrFail($id)->delete();
        
        session()->flash('message', 'Notification deleted.');
    }
    
    // This is necessary for using the component directly in a route
    public function __invoke()
    {
        return $this->render();
    }
}