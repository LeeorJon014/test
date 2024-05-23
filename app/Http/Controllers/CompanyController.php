<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\OfficeAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    public function getCompanies(Company $company, Request $request)
    {
        $this->authorize('view', $company);

        // Retrieve announcements using Eloquent
        $companies = Company::select([
            'id',
            'name',
            // format created_at month day year hour minute second in select raw query
            DB::raw("DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') as created_at_formatted"),
        ])
            ->with([
                'officeAddress'
            ])

            // Query that will filter the companies by Company name
            ->when($request->has('company_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->input('company_filter') . '%');
            })
            ->paginate(10);

        // if($request->has('company_filter')) {
        // $query->where('name', 'LIKE', '%' . $request->input('company_filter') . '%');
        // }

        // $companies = $query->paginate(10);
        // Return JSON response
        return response()->json($companies);
    }


    //so that the dropdown will get all company
    public function getAllCompanies(Company $company, Request $request)
    {
        $this->authorize('view', Company::class);

        // Retrieve all companies using Eloquent
        $allcompanies = Company::select([
            'id',
            'name',
            // format created_at month day year hour minute second in select raw query
            DB::raw("DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') as created_at_formatted"),
        ])
            ->with([
                'officeAddress'
            ])
            ->get();

        // Return JSON response
        return response()->json($allcompanies);
    }

    public function index(Company $company)
    {
        $this->authorize('view', $company);

        return Inertia::render('Company/Index', [
            'role' => in_array(Auth::user()->roles->first()?->slug, [
                'super-administrator',
                'administrative-service-officer',
                'registry-coordinator',
                'cultural-registry-data-officer',
                'precup-head',
                'geographic-information-systems-analyst',
            ]) ? 'admin' : 'user',
            'permissions' => $this->getPermissions()
        ]);
    }

    public function create(Company $company)
    {
        $this->authorize('create', $company);

        return Inertia::render('Company/Create', [
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

    public function store(Company $company, Request $request)
    {
        $this->authorize('create', $company);

        // dd($request->selectedCity);

        $request->validate([
            'company_name' => 'required|unique:companies,name|max:255',
            'street_address' => 'required|max:255',
            'barangay' => 'required|max:255',
            // 'city' => 'required|max:255',
            // 'municipality' => 'required|max:255',
            // 'province' => 'required|max:255',
            'country' => 'required|max:255',
        ]);

        // dd($request->all());
        // dd($request->input('provinceDesc'));

        // Update company fields
        $company = Company::create([
            'name' => $request->input('company_name'),
        ]);

        // Update office address fields
        $officeAddress = OfficeAddress::create([
            'street_address' => $request->input('street_address'),
            'barangay' => $request->input('barangay'),
            'city' => $request->input('selectedCity'),
            'municipality' => $request->input('selectedCity'),
            'province' => $request->input('provinceDesc'),
            'country' => $request->input('country'),
        ]);
        $company->update([
            'office_address_id' => $officeAddress->id,
        ]);

        session()->flash('status', 'Company created successfully.');

        return Inertia::render('Company/Create', [
            'status' => session('status'),
            'company_name' => $company_name ?? null,
            'street_address' => $company->officeAddress->street_address ?? null,
            'barangay' => $company->officeAddress->barangay ?? null,
            // 'city' => $company->officeAddress->city ?? null,
            'municipality' => $company->officeAddress->municipality ?? null,
            'province' => $company->officeAddress->province ?? null,
            'country' => $company->officeAddress->country ?? null,
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

    // create an edit company page
    public function edit(Company $company, $id)
    {
        $this->authorize('view', $company);

        $company = Company::find($id);

        return Inertia::render('Company/Edit', [
            'company' => $company ?? null,
            'company_name' => $company->name ?? null,
            'street_address' => $company->officeAddress->street_address ?? null,
            'barangay' => $company->officeAddress->barangay ?? null,
            'city' => $company->officeAddress->city ?? null,
            'municipality' => $company->officeAddress->municipality ?? null,
            'province' => $company->officeAddress->province ?? null,
            'country' => $company->officeAddress->country ?? null,
            'role' => in_array(Auth::user()->roles->first()?->slug, [
                'super-administrator',
                'administrative-service-officer',
                'registry-coordinator',
                'cultural-registry-data-officer',
                'precup-head',
                'geographic-information-systems-analyst',
            ]) ? 'admin' : 'user',
            'permissions' => $this->getPermissions()
        ]);
    }

    /**
     * Retrieve permissions based on user roles.
     *
     * @return array List of permissions ('create', 'update', 'delete') allowed by the user.
     */
    private function getPermissions(): array
    {
        $abilities = ['create', 'update', 'delete'];

        $permissions = [];
        foreach ($abilities as $ability) {
            if (Gate::allows($ability, Company::class)) {
                array_push($permissions, $ability);
            }
        }

        return $permissions;
    }

    public function update(Company $company, $id, Request $request)
    {
        $this->authorize('update', $company);

        $request->validate([
            'company_name' => 'required|max:255',
            'street_address' => 'required|max:255',
            'barangay' => 'required|max:255',
            // 'city' => 'required|max:255',
            // 'municipality' => 'required|max:255',
            // 'province' => 'required|max:255',
            'country' => 'required|max:255',
        ]);

        $company = Company::find($id);
        $company->update([
            'name' => $request->input('company_name'),
        ]);

        $company->officeAddress->update([
            'street_address' => $request->street_address,
            'barangay' => $request->barangay,
            'city' => $request->selectedCity,
            'municipality' => $request->selectedCity,
            'province' => $request->selectedProvince,
        ]);
        session()->flash('status', 'Company updated successfully.');

        return Inertia::render('Company/Edit', [
            'status' => session('status'),
            'company_name' => $company_name ?? null,
            'street_address' => $company->officeAddress->street_address ?? null,
            'barangay' => $company->officeAddress->barangay ?? null,
            'city' => $company->officeAddress->city ?? null,
            'municipality' => $company->officeAddress->municipality ?? null,
            'province' => $company->officeAddress->province ?? null,
            'country' => $company->officeAddress->country ?? null,
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
