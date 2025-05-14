<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('fournisseur')) {
            return redirect()->route('dashboard')->with('error', 'You must be a supplier to access this area.');
        }

        return $next($request);
    }
}