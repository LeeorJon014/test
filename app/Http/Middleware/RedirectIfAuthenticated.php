<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
 
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = 'user';
                if (
                    // Auth::user()->isAdmin()
                    Auth::user()->roles[0]->slug === 'super-administrator' ||
                    Auth::user()->roles[0]->slug === 'administrative-service-officer' ||
                    Auth::user()->roles[0]->slug === 'registry-coordinator' ||
                    Auth::user()->roles[0]->slug === 'cultural-registry-data-officer' ||
                    Auth::user()->roles[0]->slug === 'precup-head' ||
                    Auth::user()->roles[0]->slug === 'geographic-information-systems-analyst'
                ) {
                    $role = 'admin';
                }
                return redirect()->intended('/' . $role . RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
