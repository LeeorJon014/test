<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Type\NullType;
use Illuminate\Support\Facades\Gate;

class AnnouncementController extends Controller
{
    public function index(Announcement $announcement)
    {
        $this->authorize('view', $announcement);

        return Inertia::render('Announcement/Index', [
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

    // create an edit announcement page
    public function edit(Announcement $announcement, $id)
    {
        $this->authorize('update', $announcement);

        $announcement = Announcement::find($id);
        //dd(env('APP_URL'));
        return Inertia::render('Announcement/Edit', [
            'announcement' => $announcement,
            'photo_url' => '/storage/' . $announcement->photo_url,
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

    //view an announcement page for users
    public function view(Announcement $announcement, $id)
    {
        $this->authorize('view', $announcement);

        $announcement = Announcement::find($id);

        return Inertia::render('Announcement/View', [
            'announcement' => $announcement,
            'photo_url' => '/storage/' . $announcement->photo_url,
            'role' => 'user',
        ]);
    }


    public function create(Announcement $announcement)
    {
        $this->authorize('create', $announcement);

        return Inertia::render('Announcement/Create', [
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

    public function store(Announcement $announcement, Request $request)
    {
        $this->authorize('create', $announcement);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:2000',
            'photo_url' => 'required',
        ]);

        $fileName = null;
        if ($request->hasFile('photo_url')) {
            $picture = $request->file('photo_url');
            $fileName = time() . '_' . $picture->getClientOriginalName();
            $path = public_path('/storage/');
            $picture->move($path, $fileName);
        }

        Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'photo_url' => $fileName,
            'created_by' => auth()->user()->id,
        ]);

        // Set a success message in the session
        session()->flash('status', 'Announcement created successfully.');

        return Inertia::render('Announcement/Create', [
            'status' => session('status'),
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
     * Retrieve permissions based on user roles.
     *
     * @return array List of permissions ('create', 'update', 'delete') allowed by the user.
     */
    private function getPermissions(): array
    {
        $abilities = ['create', 'update', 'delete'];

        $permissions = [];
        foreach ($abilities as $ability) {
            if (Gate::allows($ability, Announcement::class)) {
                array_push($permissions, $ability);
            }
        }

        return $permissions;
    }

    public function getAnnouncements(Announcement $announcement, Request $request)
    {
        $this->authorize('view', $announcement);

        $role = in_array(Auth::user()->roles->first()?->slug, [
            'super-administrator',
            'administrative-service-officer',
            'registry-coordinator',
            'cultural-registry-data-officer',
            'precup-head',
            'geographic-information-systems-analyst',
        ]) ? 'admin' : 'user';
        // Retrieve announcements using Eloquent
        $announcements = Announcement::select([
            'id',
            'title',
            'description',
            'photo_url',
            // format created_at month day year hour minute second in select raw query
            DB::raw("DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') as created_at_formatted"),
            'created_by',
            'created_at',
        ])
            ->with([
                'creator:id,name'
            ])
            // ->when($role === 'user', function ($query) {
            //     $query->where('created_by', Auth::user()->id);
            // })

            //Query to filter the announcements
            ->when($request->has('title_filter'), function ($query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->title_filter}%");
            })
            ->when($request->has('start_date'), function ($query) use ($request) {
                $query->where('created_at', '>=', $request->input('start_date'));
            })
            ->when($request->has('end_date'), function ($query) use ($request) {
                $query->where('created_at', '<=', $request->input('end_date'));
            })
            ->paginate(10);

        /**  
         *if($request->has('title_filter')) {
         * $query->where('title', 'LIKE', '%' . $request->input('title_filter') . '%');
         *}

         *if($request->has('start_date')) {
         * $query->where('created_at', '>=', $request->input('start_date')); 
         *}

         *if($request->has('end_date')) {
         *$query->where('created_at', '<=', $request->input('end_date'));
         *}

         *$announcements = $query->paginate(10);
         */
        return response()->json($announcements);
    }

    public function update(Announcement $announcement, Request $request, $id)
    {
        $this->authorize('create', $announcement);
    
        $announcement = Announcement::findOrFail($id);
    
        if ($request->has('photo_url')) {
            $photoUrl = $request->input('photo_url');
    
            // Extract original file name from URL
            $originalFileName = $request->input('fileName');
            $fileName = time() . '_' . $originalFileName;
    
            // Save base64-encoded image data to file
            $photoData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoUrl));
            $path = public_path('/storage/');
            file_put_contents($path . $fileName, $photoData);
    
            // Update announcement with new photo URL
            $announcement->update([
                'photo_url' => $fileName
            ]);
        }
    
        $announcement->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
    
        session()->flash('status', 'Announcement updated successfully.');

        return Inertia::render('Announcement/Edit', [
            'status' => session('status'),
            'announcement' => $announcement,
            'photo_url' => '/storage/' . $announcement->photo_url,
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
     * Delete an announcement.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Announcement $announcement, $id)
    {
        $this->authorize('delete', $announcement);

        $announcement = Announcement::find($id);
        $announcement->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }
}
