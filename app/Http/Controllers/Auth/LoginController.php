<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            return redirect('/');
        }
        
        return view('auth.login');
    }

 public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Find user by email
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'No account found with this email address.',
        ])->withInput();
    }

    // Check if user is active
    if (!$user->is_active) {
        return back()->withErrors([
            'email' => 'This account has been deactivated.',
        ])->withInput();
    }

    // Check password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ])->withInput();
    }

    // Login the user
    Auth::login($user, $request->filled('remember'));

    // Update last login
    $user->update(['last_login_at' => now()]);

    $request->session()->regenerate();

    // ✅ FIXED: Correct redirect URLs
    if ($user->isAdmin()) {
        return redirect('/admin/dashboard');
    } elseif ($user->isClient()) {
        return redirect('/client/dashboard');
    } elseif ($user->isFournisseur()) {
        return redirect('/supplier/dashboard'); // ✅ FIXED: Was /fournisseur/dashboard
    }

    // Fallback redirect
    return redirect('/');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}