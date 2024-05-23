<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use App\Models\CulturalProperty;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

/**
 * Controller for the Dashboard Report Page
 * 
 * @method Response index() Returns Dashboard report page
 * @method JsonResponse getUsersCount() Returns all, verified, and active users
 * @method JsonResponse getRegionProperties() Returns the number of cultural properties per region
 * @method JsonResponse getLGUs() Returns no. of submitted LGUs, submission rate, total no. of LGUs, number of LGUs awaiting for compliance
 * @method JsonResponse getPropertyStatus() Returns all cultural property count and its published, for validation, for review, and for compliance statuses 
 * 
 */
class DashboardReportController extends Controller
{
    /**
     * Returns Dashboard report page
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('DashboardReport/Index', [
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
     * Returns all, verified, and active users
     *
     * @return JsonResponse
     */
    public function getUsersCount(): JsonResponse
    {
        $allUsers = User::all()->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $activeUsers = User::where('is_active', true)->count();

        return response()->json([
            'all' => $allUsers,
            'verified' => $verifiedUsers,
            'active' => $activeUsers
        ]);
    }

    /**
     * Returns the number of cultural properties per region
     *
     * @return JsonResponse
     */
    public function getRegionProperties(): JsonResponse
    {
        $response = [];
        $region_counts = Location::groupBy('region')->select('region', DB::raw('count(*) as total'))->get();
        $regionFullNames = [
            'Rehiyon I (Ilocos)', 'Rehiyon II (Lambak Cagayan/Cagayan Valley)', 'Rehiyon III (Gitnang Luzon/Central Luzon)', 'Rehiyon IV-A (Calabarzon)', 'Rehiyon IV-B (Mimaropa)',
            'Rehiyon V (Bicol)', 'Rehiyon VI (Kanlurang Bisayas/Western Visayas)', 'Rehiyon VII (Gitnang Bisayas/Central Visayas)', 'Rehiyon VIII (Silangang Bisayas/Eastern Visayas)', 'Rehiyon IX (Tangway ng Zamboanga/Zamboanga Peninsula)',
            'Rehiyon X (Hilagang Mindanao/Northern Mindanao)', 'Rehiyon XI (Davao)', 'Rehiyon XII (Soccsksargen)', 'Rehiyon XIII (Caraga)', 'CAR (Rehiyong Administratibo ng Cordillera/Cordillera Administrative Region)',
            'Bangsamoro (Rehiyong Awtonomo ng Bangsamoro sa Muslim Mindanao / Bangsamoro Autonomous Region in Muslim Mindanao)', 'NCR (Kalakhang Maynila / National Capital Region)',
            'Higit sa isang Rehiyong Kinasasakupan (Multiple Regions)', 'Hindi Angkop (Not Applicable)', 'Buong Bansa (Nationwide)', 'Ibayong Dagat (Overseas)'
        ];

        $regionAbbrv = ['I', 'II', 'III', 'IV-A', 'IV-B', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'Bangsamoro', 'NCR', 'Multiple Regions', 'Not Applicable', 'Nationwide', 'Overseas'];

        // set all region properties values to 0
        foreach ($regionAbbrv as $region) {
            // $response[$region] = random_int(1, 3);
            $response[$region] = 0;
        }

        // set value of each region property based on their region_counts['total']
        foreach ($region_counts as $reg_count) {
            $index = array_search($reg_count['region'], $regionFullNames);

            if (isset($index)) {
                $response[$regionAbbrv[$index]] = $reg_count['total'];
            }
        }

        return response()->json($response);
    }

    /**
     * Returns no. of submitted LGUs, submission rate, total no. of LGUs, 
     * number of LGUs awaiting for compliance
     *
     * @return JsonResponse
     */
    // public function getLGUs(): JsonResponse
    // {
    //     $totalLGU = 1716;
    //     // get number of LGUs submitted based on their region, province, and city/municipality  
    //     $submittedLGU = Location::groupBy('region', 'province', 'city_municipality')->select('city_municipality', DB::raw('count(*) as total'))->get()->count();
    //     $submissionRate = round($submittedLGU / $totalLGU * 100, 2);
    //     $lguToComply = $totalLGU - $submittedLGU;

    //     return response()->json([
    //         'total_lgu' => $totalLGU,
    //         'submitted_lgu' => $submittedLGU,
    //         'submission_rate' => $submissionRate,
    //         'lgu_to_comply' => $lguToComply
    //     ]);
    // }
    public function getLGUs(): JsonResponse
{
    // Total number of LGUs in the Philippines (fixed value)
    $totalLGU = 1716;

    // Get the total count of items in the companies table
    $totalCompanies = Company::count();

    // Number of LGUs submitted based on the total count of companies
    $submittedLGU = $totalLGU - $totalCompanies;

    // Calculate submission rate
    $submissionRate = round(($totalCompanies / $totalLGU) * 100, 2);

    return response()->json([
        'total_lgu' => $totalLGU,
        'submitted_lgu' => $submittedLGU,
        'submission_rate' => $submissionRate,
        'lgu_to_comply' => $totalLGU - $submittedLGU,
    ]);
}

    // 
    /**
     * Returns all cultural property count and its published, for validation, for review, and for compliance statuses 
     *
     * @return JsonResponse
     */
    public function getPropertyStatus(): JsonResponse
    {
        $allCulturalProps = CulturalProperty::all()->count();
        $publishedCulturalProps = Status::where('status', 'published')->count();
        $validationCulturalProps = Status::where('status', 'validation')->count();
        $reviewCulturalProps = Status::where('status', 'review')->count();
        $complianceCulturalProps = Status::where('status', 'compliance')->count();

        $response['all_props'] = CulturalProperty::all()->count();
        $response['published'] = Status::where('status', 'published')->count();
        $response['validation'] = Status::where('status', 'validation')->count();
        $response['review'] = Status::where('status', 'review')->count();
        $response['compliance'] = Status::where('status', 'compliance')->count();

        return response()->json([
            'all_props' => $allCulturalProps,
            'published' => $publishedCulturalProps,
            'validation' => $validationCulturalProps,
            'review' => $reviewCulturalProps,
            'compliance' => $complianceCulturalProps,
        ]);
    }
}
