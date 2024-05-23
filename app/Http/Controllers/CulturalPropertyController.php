<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Http\Controllers\Traits\CulturalPropertyUpdateTrait;
use App\Http\Controllers\Traits\CulturalPropertyPermissionTrait;
use App\Http\Controllers\Traits\CulturalPropertyTrait;
use App\Imports\CulturalPropertiesType1Import;
use App\Imports\CulturalPropertiesType2Import;
use App\Imports\CulturalPropertiesType3Import;
use App\Models\CulturalProperty;
use App\Models\PropertyName;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CulturalPropertyController extends Controller
{
    use CulturalPropertyTrait, CulturalPropertyPermissionTrait, CulturalPropertyUpdateTrait;

    public function index(CulturalProperty $culturalProperty): Response
    {
        $this->authorize('view', $culturalProperty);

        return Inertia::render('CulturalProperty/ViewInventory', [
            'role' => $this->checkUserRole(),
            /* Returns a variable that contains the current user's role */
            'currentUserRole' => Auth::user()->roles->first()?->slug ?? 'invalid-slug',
        ]);
    }

    /**
     * View Inventory page: Get a paginated list of cultural properties based on the provided filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCulturalProperties(CulturalProperty $culturalProperty, Request $request)
    {
        $this->authorize('view', $culturalProperty);

        $searchQuery = $request->input('property_name');
        $locations = [$request->input('region'), $request->input('province'), $request->input('city')];
        $locationsID = ["region", "province", "city_municipality"];

        $results = CulturalProperty::query();

        // Apply filters if search query or any location filter is provided
        if ($searchQuery) {
            $results->whereHas('propertyName', function ($query) use ($searchQuery) {
                $query->where('official_name', 'LIKE', '%' . $searchQuery . '%');
            });
        }
        if (
            array_filter($locations, function ($value) {
                return $value !== null;
            })
        ) {
            for ($i = 0; $i < 3; $i++) {
                if ($locations[$i]) {
                    $results->whereHas('location', function ($query) use ($locations, $locationsID, $i) {
                        $query->where($locationsID[$i], 'LIKE', '%' . $locations[$i] . '%');
                    });
                }
            }
        }

        $results = $this->getInventoryByRole($results);
        $statusConstants = CulturalProperty::STATUS;

        // Paginate the results
        $paginatedResults = $results->with(['propertyName', 'location', 'submitter'])->paginate(10);

        // Return the paginated results along with statusConstants
        return response()->json([
            'culturalProperties' => $paginatedResults,
            'statusConstants' => $statusConstants
        ]);
    }

    /*
     *View Inventory Page: Updating the status of the cultural property
     *
     *
    */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Retrieve the current user's role
            $currentUserRole = $request->user()->roles->first()?->slug;

            // Retrieve role permissions
            $rolePermissions = $this->getRolePermissions();

            // Retrieve the requested status from the request data
            $requestedStatus = $request->input('status');

            // Retrieve the current status of the cultural property
            $culturalProperty = CulturalProperty::findOrFail($id);
            $currentStatus = $culturalProperty->is_Validated;

            // Check if the requested status is allowed for the current user's role
            if (!in_array($requestedStatus, $rolePermissions[$currentUserRole]['allowed_values'])) {
                // If not allowed, return a Bad Request error
                return response()->json(['error' => 'Bad Request: Invalid status update'], 400);
            }

            // Check if the current status allows the requested change
            if (!in_array($currentStatus, $rolePermissions[$currentUserRole]['allowed_changes'])) {
                // If not allowed, return a Bad Request error
                return response()->json(['error' => 'Bad Request: Unauthorized status update'], 400);
            }

        } catch (\Exception $e) {
            // Handle any exceptions/errors related to authorization
            return response()->json(['message' => 'Validation/Authorization error'], 500);
        }

        try {
            // Update the status based on the request data
            $culturalProperty->update(['is_Validated' => $requestedStatus]);

            // Check if the status is PUBLIC VALIDATION (value 5)
            if ($requestedStatus == 5) {
                // Set the validation_started_at column to the current date and time
                $culturalProperty->update(['validation_started_at' => now()->setTimezone('Asia/Manila')]);
            }

            return response()->json(['message' => 'Status updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions/errors related to database operation
            return response()->json(['message' => 'Error updating status'], 500);
        }
    }

    /**
     * Display the Upload Form.
     *
     * @return Response
     */
    public function uploadInventoryView(CulturalProperty $culturalProperty): Response
    {
        $this->authorize('create', $culturalProperty);

        $companies = $this->getCompanyByRole();

        return Inertia::render('CulturalProperty/UploadInventory', [
            'role' => $this->checkUserRole(),
            'canSelectCompany' => $this->canSelectCompany(),
            'companies' => $companies->map(function ($company) {
                return [
                    'id'   => $company->id,
                    'name' => $company->name,
                ];
            }),
        ]);
    }

    /**
     * Handle the upload form request.
     *
     * @param Request $request
     * @return void
     */
    public function uploadInventory(CulturalProperty $culturalProperty, Request $request)
    {
        $this->authorize('create', $culturalProperty);

        // Validate the uploaded file
        $file = $request->file('file');
        try {
            Common::validateExcelFile($file);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }

        // Retrieve the selected type from the dropdown and handle the upload process.
        $type = $request->input('type');

        switch ($type) {
            case 1:
                Excel::import(new CulturalPropertiesType1Import($request->companyId), $file);
                break;
            case 2:
                Excel::import(new CulturalPropertiesType2Import($request->companyId), $file);
                break;
            case 3:
                Excel::import(new CulturalPropertiesType3Import($request->companyId), $file);
                break;
            default:
                break;
        }

        return response()->json(['message' => 'Import successful']);
    }

    /**
     * Display the Edit Form.
     *
     * @return Response
     */
    public function edit(CulturalProperty $culturalProperty, $id): Response
    {
        $this->authorize('view', $culturalProperty);

        if (Auth::user()->roles->first()?->slug === 'super-administrator') {
            $culturalProperty = CulturalProperty::find($id);
        } else {
            $culturalProperty = CulturalProperty::where([
                ['id', '=', $id],
                ['company_id', '=', Auth::user()->company_id],
            ])->first();
        }

        if (null === $culturalProperty) {
            abort(403, 'You do not have permission to perform this action.');
        }
        
        $propertyName = PropertyName::select('official_name')
            ->where('id', $culturalProperty->getPropertyNameId())
            ->value('official_name');

        $companies = $this->getCompanyByRole();

        return Inertia::render('CulturalProperty/EditInventory', [
            'id' => $id,
            'propertyName' => $propertyName,
            'formType' => 1,
            'role' => $this->checkUserRole(),
            'editPermissions' => $this->getEditPermissions(),
            'canSelectCompany' => $this->canSelectCompany(),
            'companies' => $companies->map(function ($company) {
                return [
                    'id'   => $company->id,
                    'name' => $company->name,
                ];
            }),
        ]);
    }

    /**
     * Edit Inventory page: Retrieve information about the cultural property.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getCulturalProperty(CulturalProperty $culturalProperty, $id, $formType)
    {
        $this->authorize('view', $culturalProperty);

        $form_id = CulturalProperty::find($id)->getFormId();

        switch ($formType) {
            case 1:
                // GENERAL EDIT PAGE
                $form = [
                    'propertyName',
                    'company',
                    'location',
                    'description' => function ($query) use ($form_id) {
                        switch ($form_id) {
                            case 1:
                                return $query->with(['f12ConditionResponses', 'generalThreatLevels', 'potentialThreatLevels']);
                            case 2:
                                return $query->with(['f12ConditionResponses', 'generalThreatLevels', 'potentialThreatLevels']);
                            case 3:
                                return $query->with(['generalThreatLevels', 'potentialThreatLevels']);
                            default:
                                throw new \Exception('Invalid request', 500);
                        }
                    },
                    'declaration' => function ($query) use ($form_id) {
                        switch ($form_id) {
                            case 1:
                                return $query->with(['recognitionsNonculturalBody', 'nationalDeclaration', 'localDeclaration']);
                            case 2:
                                return $query->with(['nationalDeclaration', 'localDeclaration']);
                            case 3:
                                return $query->with(['nationalDeclaration', 'localDeclaration']);
                            default:
                                throw new \Exception('Invalid request', 500);
                        }
                    },
                    'significance' => function ($query) {
                        return $query->with([
                            'primaryCriteria',
                            'comparativeCriteria',
                            'areaOfSignificance',
                        ]);
                    },
                    'submitter',
                ];
                break;

            case 2:
                // MISCELLANEOUS PAGE
                $form = $this->getExclusiveForms($form_id);

                switch ($form_id) {
                    case 1:
                        $form['f1CurrentUse'] = function ($query) {
                            $query->with('f1CurrentUseResponse');
                        };
                        break;
                    case 2:
                        break;
                    case 3:
                        $form['f3RelatedDomain'] = function ($query) {
                            $query->with(['f3GivenRelatedDomain', 'f3GivenSupportingDocu']);
                        };
                        $form['f3SafeguardingMeasures'] = function ($query) {
                            $query->with('f3GivenKindOfMeasure');
                        };
                        break;
                    default:
                        throw new \Exception('Invalid request on Miscellaneous page.', 500);
                }
                break;
        };
        $query = CulturalProperty::with($form);

        $query = $this->getInventoryByRole($query);

        return $query->find($id);
    }

    /**
     * Display the Miscellaneous page where it contains the form-exclusive sections
     * of editing inventories.
     *
     * @return Response
     */
    public function miscellaneousView(CulturalProperty $culturalProperty, $id): Response
    {
        $this->authorize('view', $culturalProperty);

        $exclusiveForms = $this->getExclusiveForms(CulturalProperty::find($id)->getFormId());
        $propertyName = PropertyName::select('official_name')
            ->where('id', CulturalProperty::find($id)->getPropertyNameId())
            ->value('official_name');

        return Inertia::render('CulturalProperty/FormExclusive', [
            'id' => $id,
            'exclusiveForms' => $exclusiveForms,
            'propertyName' => $propertyName,
            'role' => $this->checkUserRole(),
            'editPermissions' => $this->getEditPermissions()
        ]);
    }

    private function checkUserRole()
    {
        return in_array(Auth::user()->roles->first()?->slug, [
            'super-administrator',
            'administrative-service-officer',
            'registry-coordinator',
            'cultural-registry-data-officer',
            'precup-head',
            'geographic-information-systems-analyst',
        ]) ? 'admin' : 'user';
    }

    /**
     * Handle the edit form request.
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkUpdatePermissions($request->update_request_type)) {
            abort(403, 'You do not have permission to perform this action.');
        }
        $notAllowedFlag = true;
        if (Auth::user()->roles->first()?->slug === 'super-administrator') {
            $notAllowedFlag = false;
        } else {
            $culturalProperty = CulturalProperty::where([
                ['id', '=', $id],
                ['company_id', '=', Auth::user()->company_id],
            ])->first();
            
            if ($culturalProperty && Auth::user()->roles->first()?->slug === 'relevant-interested-party') {
                $notAllowedFlag = false;
            }
        }

        if ($notAllowedFlag) {
            abort(403, 'You do not have permission to perform this action.');
        }

        try {
            switch ($request->update_request_type) {
                case 'propertyName':
                    return $this->updatePropertyName($request, $id);
                case 'location':
                    return $this->updateLocationName($request, $id);
                case 'description':
                    return $this->updateDescription($request, $id);
                case 'declaration':
                    return $this->updateDeclaration($request, $id);
                case 'significance':
                    return $this->updateSignificance($request, $id);
                case 'submitter':
                    return $this->updateSubmitter($request, $id);
                case 'f12Classification':
                    return $this->updateF12Classification($request, $id);
                case 'f1LegalDescription':
                    return $this->updateF1LegalDescription($request, $id);
                case 'f1CurrentUse':
                    return $this->updateF1CurrentUse($request, $id);
                case 'f12Ownership':
                    return $this->updateF12Ownership($request, $id);
                case 'f2Reference':
                    return $this->updateF2Reference($request, $id);
                case 'f2StoryHeritage':
                    return $this->updateF2StoryHeritage($request, $id);
                case 'f3CulturalBearer':
                    return $this->updateF3CulturalBearer($request, $id);
                case 'f3RelatedDomain':
                    return $this->updateF3RelatedDomain($request, $id);
                case 'f3Description':
                    return $this->updateF3Description($request, $id);
                case 'f3SafeguardingMeasure':
                    return $this->updateF3SafeguardingMeasure($request, $id);
                default:
                    throw new \Exception('Invalid request', 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating CulturalProperty'], 500);
        }
    }

    public function getRolePermissions()
    {
        return [
            'super-administrator' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['PENDING'], 
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['NONCOMPLIANT'],
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'],
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'],
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA'],
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['PENDING'], 
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['NONCOMPLIANT'], 
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
            'administrative-service-officer' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['PENDING'], 
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['NONCOMPLIANT'], 
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['PENDING'], 
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['NONCOMPLIANT'], 
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
            'registry-coordinator' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['REVIEW'], 
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['COMPLIANT'], 
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
            'cultural-registry-data-officer' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'], 
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['REVIEW'], 
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
            'precup-head' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['ACCESSIONING'], 
                    CulturalProperty::STATUS['PUBLIC VALIDATION'], 
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
            'geographic-information-systems-analyst' => [
                'allowed_values' => [
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA'],
                ],
                'allowed_changes' => [
                    CulturalProperty::STATUS['PUBLISHED IN SITE'], 
                    CulturalProperty::STATUS['PROCESSING FOR MAPAMANA'], 
                    CulturalProperty::STATUS['PUBLISHED IN MAPAMANA']
                ],
            ],
        ];
    }

    /*
    *
    * Soft Delete in View Inventory
    * 
    */
    public function destroy($id, Request $request)
    {
        try {
            // Check if user is authorized to soft delete
            // Retrieve the current user's role
            $currentUserRole = $request->user()->roles->first()?->slug;

            // Check if the current status allows the requested change
            if ($currentUserRole != 'super-administrator' && $currentUserRole != 'relevant-interested-party') {
                // If not allowed, return a Bad Request error
                return response()->json(['message' => 'Bad Request: Role Unauthorized for Deletion'], 400);
            }
        } catch (\Exception $e) {
            // Handle any exceptions/errors related to authorization
            return response()->json(['message' => 'Role Validation/Authorization error'], 500);
        }
        try {
            // Validate Reason
            $validatedData = $request->validate([
                'reason' => 'required|string|max:200', // Ensure reason is required, a string, and has a maximum length of 200 characters
            ]);
            $reason = $this->sanitizeReason($validatedData['reason']);
            $reason = $request->input('reason');
            
            // Find the cultural property
            $culturalProperty = CulturalProperty::findOrFail($id);
            
            // Save the sanitized reason in the deletion_reason column
            $culturalProperty->update(['deletion_reason' => $reason]);
            // Perform soft deletion
            $culturalProperty->delete();

            // Redirect with a flash message or to a specific route
            if ($currentUserRole === 'super-administrator') {
                return redirect()->route('admin.view.inventory')->with('success', 'Cultural property deleted successfully.');
            }
            if ($currentUserRole === 'relevant-interested-party') {
                return redirect()->route('user.view.inventory')->with('success', 'Cultural property deleted successfully.');
            } 
        } catch (\Exception $e){
            \Log::error('Error deleting cultural property: '.$e->getMessage());
            return response()->json(['message' => 'Error Deleting Cultural Property '], 500);
        }
    }

    /**
     * Sanitize the reason input to prevent SQL injection
     *
     * @param string $reason
     * @return string
     */
    private function sanitizeReason($reason)
    {
        // Use Laravel's query builder to escape special characters and prevent SQL injection
        return DB::connection()->getPdo()->quote($reason);
    }
}
