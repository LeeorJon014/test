<?php

namespace App\Http\Controllers;

use App\Models\PropertyName;
use Illuminate\Http\Request;


class PropertyNameController extends Controller
{
    public function update(Request $request, $id)
    {        
        PropertyName::findOrFail($id)->update([
            'official_name' => $request->data['official_name'],
            'common_name' => $request->data['common_name'],
            'filipino_name' => $request->data['filipino_name'],
        ]);
        
        return response()->json(['message' => 'Property Names updated successfully']);
    }
}
