<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendEmailVerification;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $roles = Role::all();

        return Inertia::render('Auth/Register', [
            'roles' => $roles,
            'role' => in_array(Auth::user()->roles->first()?->slug, [
                'super-administrator',
                'administrative-service-officer',
                'registry-coordinator',
                'cultural-registry-data-officer',
                'precup-head',
                'geographic-information-systems-analyst',
            ]) ? 'admin' : 'user',
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', 'max:50', 'string', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role_id) {
            $role = Role::find($request->role_id);
            $user->roles()->sync([$role->id]);
        }

        event(new SendEmailVerification($user, $request->password));

        return Inertia::render('Auth/Register', [
            'status' => session('status'),
            'user_roles' => $user->roles ?? null,
            'role' => in_array(Auth::user()->roles->first()?->slug, [
                'super-administrator',
                'administrative-service-officer',
                'registry-coordinator',
                'cultural-registry-data-officer',
                'precup-head',
                'geographic-information-systems-analyst',
            ]) ? 'admin' : 'user',
        ]);
    }
}