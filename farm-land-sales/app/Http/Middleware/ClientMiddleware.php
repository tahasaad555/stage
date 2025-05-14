<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('client')) {
            return redirect()->route('dashboard')->with('error', 'You must be a client to access this area.');
        }

        return $next($request);
    }
}