<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function update(Request $request, $id)
    {        
        Location::findOrFail($id)->update([
            'street_address' => $request->data['street_address'],
            'barangay' => $request->data['barangay'],
            'city_municipality' => $request->data['city_municipality'],
            'province' => $request->input('data.province.title'),
            'region' => $request->input('data.region.title'),
            'cluster' => $request->data['cluster'],
            'longitude' => $request->data['longitude'],
            'latitude' => $request->data['latitude'],
            'neighbouring_places' => $request->data['neighbouring_places'],
        ]);
        
        return response()->json(['message' => 'Location updated successfully']);
    }
}
