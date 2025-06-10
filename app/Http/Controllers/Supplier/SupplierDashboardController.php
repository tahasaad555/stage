<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierDashboardController extends Controller
{
    /**
     * Show the supplier dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // You can add more data here as needed
        $data = [
            'user' => $user,
            'properties' => collect(), // Replace with actual property listings
            'inquiries' => collect(), // Replace with actual inquiries
            'notifications' => collect(), // Replace with actual notifications
            'stats' => [
                'total_properties' => 0,
                'active_properties' => 0,
                'total_inquiries' => 0,
                'monthly_views' => 0,
            ]
        ];

        return view('supplier.dashboard', $data);
    }

    /**
     * Show the supplier profile.
     */
    public function profile()
    {
        return view('supplier.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the supplier profile.
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