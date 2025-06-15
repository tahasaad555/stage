<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ClientSettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $user = auth()->user();
        return view('client.settings.index', compact('user'));
    }

    /**
     * Update user settings.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
            'marketing_emails' => 'boolean',
            'language' => 'in:en,fr,ar',
            'timezone' => 'string|max:50',
            'currency' => 'in:EUR,USD,MAD',
        ]);

        // Update user preferences (you might need to add these fields to users table or create a preferences table)
        $user->update([
            'preferences' => json_encode([
                'notifications_email' => $request->boolean('notifications_email'),
                'notifications_sms' => $request->boolean('notifications_sms'),
                'marketing_emails' => $request->boolean('marketing_emails'),
                'language' => $request->language ?? 'en',
                'timezone' => $request->timezone ?? 'UTC',
                'currency' => $request->currency ?? 'EUR',
            ])
        ]);

        return back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:DELETE',
        ]);

        $user = auth()->user();

        // Check if password is correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password is incorrect.']);
        }

        // Logout and delete account
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}