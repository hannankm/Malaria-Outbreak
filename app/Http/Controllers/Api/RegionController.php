<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Http\Resources\RegionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    // Display a listing of the regions
    public function index()
    {
        // Use RegionResource to format the response
        return RegionResource::collection(Region::with('zones.woredas')->get());
    }

    // Store a newly created region
    public function store(Request $request)
    {
        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return a JSON response with validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $region = Region::create([
            'name' => $request->name,
        ]);

        // Wrap the created resource in RegionResource
        return new RegionResource($region);
    }

    // Display the specified region
    public function show($id)
{
    // Find the region by ID with its related zones and woredas
    $region = Region::with('zones.woredas')->find($id);

    // Check if the region exists
    if (!$region) {
        return response()->json(['message' => 'Region not found'], 404);
    }

    // Return the formatted response using RegionResource
    return new RegionResource($region);
}

    // Update the specified region
    public function update(Request $request, Region $region)
    {
        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return a JSON response with validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $region->update([
            'name' => $request->name,
        ]);

        // Wrap the updated resource in RegionResource
        return new RegionResource($region);
    }

    // Remove the specified region
    public function destroy($id)
{
    // Fetch the region by its ID
    $region = Region::find($id);

    if (!$region) {
        // Return a 404 response if the region is not found
        return response()->json(['message' => 'Region not found'], 404);
    }

    // Delete the region
    $region->delete();

    // Return a JSON response with a success message
    return response()->json(['message' => 'Region deleted successfully'], 200);
}

}
