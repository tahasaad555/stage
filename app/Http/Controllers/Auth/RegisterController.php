<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Use database transaction to ensure both User and related records are created
        DB::beginTransaction();
        
        try {
            $user = $this->create($request->all());
            
            event(new Registered($user));
            
            Auth::login($user);
            
            DB::commit();
            
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath($user));
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors([
                'email' => 'Registration failed. Please try again. Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:client,supplier'],
            'terms' => ['required', 'accepted'],
        ];

        // Add validation for supplier-specific fields
        if (isset($data['role']) && $data['role'] === 'supplier') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['business_registration'] = ['required', 'string', 'max:100', 'unique:fournisseurs,business_registration'];
            $rules['address'] = ['required', 'string', 'max:500'];
        }

        // Add validation for client-specific fields  
        if (isset($data['role']) && $data['role'] === 'client') {
            $rules['specialization_type'] = ['nullable', 'string', 'max:255'];
            $rules['preferences'] = ['nullable', 'string', 'max:1000'];
        }

        return Validator::make($data, $rules, [
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
            'company_name.required' => 'Company name is required for suppliers.',
            'business_registration.required' => 'Business registration number is required for suppliers.',
            'business_registration.unique' => 'This business registration number is already in use.',
            'address.required' => 'Business address is required for suppliers.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        // Convert 'supplier' to 'fournisseur' for database consistency
        $role = $data['role'] === 'supplier' ? 'fournisseur' : $data['role'];
        
        // Create the user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $role,
            'is_active' => true,
            'email_verified_at' => null, // Will be set when email is verified
        ]);

        // Create role-specific records
        if ($role === 'client') {
            $this->createClientRecord($user, $data);
        } elseif ($role === 'fournisseur') {
            $this->createFournisseurRecord($user, $data);
        }

        return $user;
    }

    /**
     * Create client record for the user
     */
    protected function createClientRecord(User $user, array $data)
    {
        Client::create([
            'user_id' => $user->id,
            'specialization_type' => $data['specialization_type'] ?? null,
            'preferences' => $data['preferences'] ?? null,
        ]);
    }

    /**
     * Create fournisseur record for the user
     */
    protected function createFournisseurRecord(User $user, array $data)
    {
        Fournisseur::create([
            'user_id' => $user->id,
            'company_name' => $data['company_name'],
            'business_registration' => $data['business_registration'],
            'address' => $data['address'],
        ]);
    }

    /**
     * The user has been registered.
     */
    protected function registered(Request $request, $user)
    {
        // Send email verification
        $user->sendEmailVerificationNotification();
        
        $message = 'Registration successful! ';
        
        if ($user->role === 'fournisseur') {
            $message .= 'Your supplier account has been created. ';
        } elseif ($user->role === 'client') {
            $message .= 'Your client account has been created. ';
        }
        
        $message .= 'Please check your email to verify your account.';
        
        return redirect()->route('verification.notice')
            ->with('success', $message);
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
        } elseif ($user->isFournisseur()) {
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
        } elseif ($user->isFournisseur()) {
            return redirect('/supplier/dashboard');
        }

        return redirect('/');
    }
}