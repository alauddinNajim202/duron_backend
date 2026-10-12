<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->status == 'active') {
            $user = Auth::guard('web')->user();

            if ($user->hasRole('developer')) {
                return redirect()->route('developer.dashboard');
            } elseif ($user->isBackendUser()) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login');
            }
        }
        return $next($request);
    }
}

