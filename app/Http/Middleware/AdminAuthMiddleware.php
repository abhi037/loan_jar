<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('admin');
        if (!Auth::guard('admin')->check()) { 
            return redirect()->route('admin.login')->with('error', 'Access denied. Please log in first.');
        }

       

        return $next($request);
    }
}
