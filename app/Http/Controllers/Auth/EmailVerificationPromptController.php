<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        // If user has already verified their email and is_active is true when logging in
        if ($request->user()->is_active) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Otherwise, render verification page
        return Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
