<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\CulturalProperty;
use Illuminate\Support\Facades\Auth;


trait CulturalPropertyPermissionTrait
{
    use HandlesAuthorization;


    public function canSelectCompany(): bool
    {
        if (Auth::user()->roles->first()?->slug === 'relevant-interested-party') {
            return false;
        }
        return true;
    }

    /**
     * Get edit permissions based on user roles.
     *
     * @return array An array containing edit permissions. If the user has 'updateAll' permission, returns ['updateAll'].
     * Otherwise, returns an array of permissions based on role-exclusive edit sections.
     */
    public function getEditPermissions(): array
    {
        if (Gate::allows('updateAll', CulturalProperty::class)) {
            return ['updateAll'];
        }

        $permissions = [];

        foreach ($this->getRoleExclusiveEditSections() as $key => $gate) {
            if (Gate::allows($gate, CulturalProperty::class)) {
                array_push($permissions, $key);
            }
        }

        return $permissions;
    }


    /**
     * Define a dictionary mapping update request types to permissions.
     *
     * @return array 
     */
    private function getRoleExclusiveEditSections(): array
    {
        return [
            'location' => 'updateLocation',
        ];
    }


    /**
     * Check if the user has permission to perform the specified update action.
     *
     * @param  string  $updateRequestType  The type of update request (e.g. 'location').
     * @return bool  Returns true if the user has permission, otherwise false.
     */
    public function checkUpdatePermissions($updateRequestType): bool
    {
        if (Gate::allows('updateAll', CulturalProperty::class)) {
            return true;
        }

        $permissionMap = $this->getRoleExclusiveEditSections();

        return isset($permissionMap[$updateRequestType]) && Gate::allows($permissionMap[$updateRequestType], CulturalProperty::class);
    }
}
