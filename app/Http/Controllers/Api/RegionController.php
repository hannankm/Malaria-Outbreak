<?php

namespace App\Http\Controllers\Api;
use App\Models\Region;
use App\Http\Resources\RegionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    // Display a listing of the regions
    public function index(Request $request)
{
    // Check for a 'search' query parameter in the request
    $search = $request->query('search');

    // Build the query for regions
    $regionsQuery = Region::with('zones.woredas');

    // If the 'search' parameter is provided, filter the regions by name
    if ($search) {
        $regionsQuery->where('name', 'like', '%' . $search . '%');
    }

    // Execute the query and return the formatted response
    return RegionResource::collection($regionsQuery->get());
}

// In RegionController.php
public function dropdown(Request $request)
{
    $search = $request->query('search'); // Optional search parameter

    $regionsQuery = Region::query();

    if ($search) {
        $regionsQuery->where('name', 'like', '%' . $search . '%');
    }

    return response()->json(
        $regionsQuery->select('id', 'name')->get()
    );
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
