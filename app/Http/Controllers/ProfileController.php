<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        session()->flash('status', '');
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user instanceof MustVerifyEmail,
            'status' => session('status'),
            'company_name' => $request->user()->company->name ?? null,
            'street_address' => $request->user()->company->officeAddress->street_address ?? null,
            'barangay' => $request->user()->company->officeAddress->barangay ?? null,
            'city' => $request->user()->company->officeAddress->city ?? null,
            'municipality' => $request->user()->company->officeAddress->municipality ?? null,
            'province' => $request->user()->company->officeAddress->province ?? null,
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
     * Update the user's profile information.
     *
     * @param ProfileUpdateRequest $request
     * @return Response
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        session()->flash('status', 'Profile updated successfully.');
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user instanceof MustVerifyEmail,
            'status' => session('status'),
            'company_name' => $request->user()->company->name ?? null,
            'street_address' => $request->user()->company->officeAddress->street_address ?? null,
            'barangay' => $request->user()->company->officeAddress->barangay ?? null,
            'city' => $request->user()->company->officeAddress->city ?? null,
            'municipality' => $request->user()->company->officeAddress->municipality ?? null,
            'province' => $request->user()->company->officeAddress->province ?? null,
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
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
