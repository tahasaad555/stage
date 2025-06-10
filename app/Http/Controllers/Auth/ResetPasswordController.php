<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's login screen with a status message. If there is an
        // error we can redirect them back to where they came from with their error.
        if ($status === Password::PASSWORD_RESET) {
            // Log the user in after successful password reset
            $user = \App\Models\User::where('email', $request->email)->first();
            Auth::login($user);
            
            // Redirect to appropriate dashboard
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard')->with('success', 'Password has been reset successfully!');
            } elseif ($user->isClient()) {
                return redirect('/client/dashboard')->with('success', 'Password has been reset successfully!');
            } elseif ($user->isSupplier()) {
                return redirect('/supplier/dashboard')->with('success', 'Password has been reset successfully!');
            }
            
            return redirect('/')->with('success', 'Password has been reset successfully!');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}