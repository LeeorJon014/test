<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controller for handling users information 
 *
 * @method Response index() return Users page
 * @method JsonResponse getUsers() return paginated users information
 */
class UserController extends Controller
{
    /**
     * return Users page
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
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
     * return paginated users information
     *
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $is_active = $request->input('is_active');

        // Start with the base query
        $query = User::query();

        // Check if $name or $is_active is provided
        if ($name || $is_active !== null) {
            if ($name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            }

            if ($is_active !== null) {
                $query->where('is_active', $is_active);
            }
        }

        $users = $query->with([
            'company',
            'roles',
        ])->paginate(10);

        foreach ($users as $user) {
            $user->formatted_created_at = Carbon::parse($user->created_at)->format('M. d, Y | h:m A ');
            $user->formatted_updated_at = Carbon::parse($user->updated_at)->format('M. d, Y | h:m A ');
            $user->formatted_email_verified_at = Carbon::parse($user->email_verified_at)->format('M. d, Y | h:m A');
        }

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $Name = $request->input('name');
        $Email = $request->input('email');
        $Password = $request->input('password');
        $RelevantInterestedPartyId = $request->input('company_id');
        $roleId = $request->input('role_id');

        $users = user::create([
            'name' => $Name,
            'email' => $Email,
            'password' => $Password,
            'company_id' => $RelevantInterestedPartyId,
        ]);

        if ($roleId) {
            $users->roles()->sync([$roleId]);
        }
        return response()->json($users);
    }

    public function edit($userId): Response
    {
        $user = User::find($userId);
        $companies = Company::select('id', 'name')->get();

        $roles = Role::select([
            'id',
            'name',
        ])->get();

        session()->flash('status', '');
        return Inertia::render('Users/EditProfile', [
            'userId' => $userId,
            'status' => session('status'),
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'contact_person' => $user->contact_person ?? null,
            'contact_number' => $user->contact_number ?? null,
            'email_verified_at' => $user->email_verified_at ?? null,
            'company_id' => $user->company_id ?? null,
            'companies' => $companies,
            'user_roles' => $user->roles ?? null,
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
     * Update user information
     *
     * @param int $userId
     * @param Request $request
     * @return Response
     */
    public function update($userId, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'max:2000',
                'email',
                Rule::unique('users', 'email')->ignore($userId), // Assuming you have the current user's ID
            ],
            'contact_person' => 'nullable|max:100',
            'contact_number' => 'nullable|max:50',
            'company_id'  => 'nullable|integer|exists:companies,id',
            'role_id'  => 'nullable|integer|exists:roles,id',
        ]);

        // Find the user by ID
        $user = User::find($userId);

        // Update user fields
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'contact_person' => $request->input('contact_person') ?? null,
            'contact_number' => $request->input('contact_number') ?? null,
            'company_id' => $request->input('company_id') ?? null,
        ]);

        $role = null;
        if ($request->input('role_id')) {
            $role = Role::find($request->input('role_id'));
            $user->roles()->sync([$role->id]);
        }

        session()->flash('status', 'User updated successfully.');

        return Inertia::render('Users/EditProfile', [
            'status' => session('status'),
            'userId' => $userId,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'contact_person' => $user->contact_person ?? null,
            'contact_number' => $user->contact_number ?? null,
            'email_verified_at' => $user->email_verified_at ?? null,
            'company_name' => $user->company_id ?? null,
            'user_role' => $role ?? null,
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

    public function delete($id)
    {
        $users = user::find($id);
        $users->delete();

        return Redirect::route('userprofile.delete');
    }
}
