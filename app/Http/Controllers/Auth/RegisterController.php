<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (auth()->check()) {
            return $this->redirectToDashboard();
        }
        
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath($user));
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:client,supplier'],
            'terms' => ['required', 'accepted'],
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select your role.',
            'role.in' => 'Please select a valid role.',
            'terms.required' => 'You must accept the terms and conditions.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => true,
            'email_verified_at' => null, // Will be set when email is verified
            'newsletter_subscribed' => isset($data['newsletter']),
        ]);
    }

    /**
     * The user has been registered.
     */
    protected function registered(Request $request, $user)
    {
        // Send email verification
        $user->sendEmailVerificationNotification();
        
        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Get the post-registration redirect path.
     */
    protected function redirectPath($user)
    {
        if ($user->isAdmin()) {
            return '/admin/dashboard';
        } elseif ($user->isClient()) {
            return '/client/dashboard';
        } elseif ($user->isSupplier()) {
            return '/supplier/dashboard';
        }

        return '/home';
    }

    /**
     * Redirect to appropriate dashboard if already logged in.
     */
    protected function redirectToDashboard()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isClient()) {
            return redirect('/client/dashboard');
        } elseif ($user->isSupplier()) {
            return redirect('/supplier/dashboard');
        }

        return redirect('/');
    }
}