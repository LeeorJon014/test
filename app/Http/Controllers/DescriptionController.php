<?php

namespace App\Http\Controllers;

use App\Models\Description;
use Illuminate\Http\Request;

class DescriptionController extends Controller
{
    public function update(Request $request, $id)
    {        
        dd($request);
        Description::findOrFail($id)->update([

        ]);
        
        return response()->json(['message' => 'Description updated successfully']);
    }
}
