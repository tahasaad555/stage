<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    /**
     * Show the client dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // You can add more data here as needed
        $data = [
            'user' => $user,
            'recent_properties' => collect(), // Replace with actual property data
            'saved_properties' => collect(), // Replace with actual saved properties
            'notifications' => collect(), // Replace with actual notifications
        ];

        return view('client.dashboard', $data);
    }

    /**
     * Show the client profile.
     */
    public function profile()
    {
        return view('client.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the client profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        return back()->with('success', 'Profile updated successfully!');
    }
}