<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiTokenController;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): mixed
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if user is verified and activated
        if (!!Auth::user()->email_verified_at && Auth::user()->is_active) {
            if ((new LoginRequest())->isPasswordOutdated()) {
                return redirect()->route('password.reset');
            }
            
            // User is verified and activated with up-to-date credentials

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

        if (is_null(Auth::user()->email_verified_at)) {
            return Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
        }

        // Verified email but still inactive
        return redirect()->route('password.reset');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
