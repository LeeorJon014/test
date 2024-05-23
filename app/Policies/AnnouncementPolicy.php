<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class AnnouncementPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-administrator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return
            $user->hasRole('super-administrator') ||
            $user->hasRole('relevant-interested-party') ||
            $user->hasRole('administrative-service-officer') ||
            $user->hasRole('registry-coordinator') ||
            $user->hasRole('cultural-registry-data-officer') ||
            $user->hasRole('precup-head') ||
            $user->hasRole('geographic-information-systems-analyst');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return
            $user->hasRole('super-administrator') ||
            $user->hasRole('registry-coordinator') ||
            $user->hasRole('precup-head');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return
            $user->hasRole('super-administrator') ||
            $user->hasRole('registry-coordinator') ||
            $user->hasRole('precup-head');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasRole('precup-head');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return true;
    }
}
