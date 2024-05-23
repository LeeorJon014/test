<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Company;


trait CulturalPropertyTrait
{
    /**
     * Filter cultural properties query based on user roles.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getInventoryByRole($query)
    {
        return $query
            ->when(
                in_array(Auth::user()->roles->first()?->slug, [
                    'relevant-interested-party',
                    'administrative-service-officer',
                    'registry-coordinator',
                    'cultural-registry-data-officer',
                    'precup-head',
                    'geographic-information-systems-analyst',
                ]),
                function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                }
            )
            ->with('company');
    }


    /**
     * Filter company query based on user roles.
     * 
     * @return \Illuminate\Support\Collection 
     **/
    public function getCompanyByRole()
    {
        if (Auth::user()->roles->first()?->slug === 'relevant-interested-party') {
            $companyId = Auth::user()->company_id;
            return Company::select('id', 'name')->where('id', '=', $companyId)->get();
        }

        return Company::select('id', 'name')->get();
    }
}
