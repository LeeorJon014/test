<?php

namespace App\Policies;

use App\Models\CulturalProperty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CulturalPropertyPolicy
{
    use HandlesAuthorization;

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
            $user->hasRole('relevant-interested-party') ||
            $user->hasRole('cultural-registry-data-officer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateAll(User $user): bool
    {
        return
            $user->hasRole('super-administrator') ||
            // SOON for editing: RIP->id === $culturalProperty->companyId
            // $user->id === $culturalProperty->id;
            $user->hasRole('relevant-interested-party') ||
            $user->hasRole('cultural-registry-data-officer') ||
            $user->hasRole('precup-head');
    }

    /**
     * Determine whether the user can update the location model.
     */
    public function updateLocation(User $user): bool
    {
        return
            $user->hasRole('geographic-information-systems-analyst');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CulturalProperty $culturalProperty): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CulturalProperty $culturalProperty): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CulturalProperty $culturalProperty): bool
    {
        return true;
    }
}
