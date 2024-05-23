<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class UpdatePasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'max:50', 'string', Rules\Password::defaults()],
        ]);

        $user = auth()->user();

        // Check if the user provided the correct current password according to the database
        if (Hash::check($request->current_password, $user->password)) {

            // Update the user's password with the new password
            $user->update([
                'password' => Hash::make($request->password),
                'is_active' => TRUE,
                'updated_at' => Carbon::now(),
            ]);

            $role = 'user';
            if (
                Auth::user()->roles[0]->slug === 'super-administrator' ||
                Auth::user()->roles[0]->slug === 'administrative-service-officer' ||
                Auth::user()->roles[0]->slug === 'registry-coordinator' ||
                Auth::user()->roles[0]->slug === 'cultural-registry-data-officer' ||
                Auth::user()->roles[0]->slug === 'precup-head' ||
                Auth::user()->roles[0]->slug === 'geographic-information-systems-analyst'
            ) {
                $role = 'admin';
            }
            // Redirect the user back to the page with a success message
            return redirect()->intended('/' . $role . RouteServiceProvider::HOME);
            // $request->session()->flash('status', 'Password reset successfully. Logging out in 5 seconds...');

            // sleep(3);

            // Auth::logout();
        }

        // If the user did not provide the correct current password,
        // redirect them back to the page with an error message
        return redirect()->back()->withErrors(['current_password' => 'The current password does not match the old password.']);
    }
}
