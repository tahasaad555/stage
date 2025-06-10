<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupplierMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'You must be logged in to access this page.');
        }

        if (!auth()->user()->isSupplier()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            
            // Redirect to appropriate dashboard based on role
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard')->with('error', 'You are already logged in as an administrator.');
            } elseif ($user->isClient()) {
                return redirect('/client/dashboard')->with('error', 'You are already logged in as a client.');
            }
            
            return redirect('/login')->with('error', 'You must be a supplier to access this page.');
        }

        return $next($request);
    }
}