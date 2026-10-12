<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebCustomRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->status == 'active') {
            $user = Auth::guard('web')->user();

            if ($user->hasRole('developer')) {
                return redirect()->intended(route('developer.dashboard', absolute: false));
            }

            if ($user->isBackendUser()) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'You do not have access to the administrative panel.']);
        }

        return redirect()->intended(route('home', absolute: false));
    }
}
