<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminGuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AdminGuestMiddleware executed');

        if (auth('admin')->check()) { 
            return redirect()->route('admin.dashboard'); // Redirect if already logged in
        }

        return $next($request);
    }
}
