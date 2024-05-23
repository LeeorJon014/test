<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::user() && Auth::user()->email_verified_at && !Auth::user()->is_active) {
            return redirect()->route('password.reset');
        }

        if (Auth::user() && is_null(Auth::user()->email_verified_at)) {
            Auth::logout();
            abort(404);
        }

        return $next($request);
    }
}
