<?php

namespace App\Http\Controllers\Api;

use App\Models\Zone;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ZoneResource;
use App\Http\Controllers\Controller;


class ZoneController extends Controller
{
    // Display a listing of the zones for a specific region
    public function index(Request $request, $regionId)
    {
        // Find the region by ID
        $region = Region::findOrFail($regionId);
    
        // Retrieve the 'search' query parameter
        $search = $request->query('search');
    
        // Build the query for zones related to the region
        $zonesQuery = $region->zones();
    
        // If the 'search' parameter is provided, filter zones by name
        if ($search) {
            $zonesQuery->where('name', 'like', '%' . $search . '%');
        }
    
        // Execute the query and return the response using ZoneResource
        return ZoneResource::collection($zonesQuery->get());
    }
    
    // Store a newly created zone
    public function store(Request $request, $regionId)
    {
        $region = Region::findOrFail($regionId);

        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $zone = $region->zones()->create([
            'name' => $request->name,
        ]);

        // Wrap the created zone in ZoneResource
        return new ZoneResource($zone);
    }

    // Display the specified zone
    public function show($regionId, $id)
    {
        $region = Region::findOrFail($regionId);
        $zone = $region->zones()->findOrFail($id);

        // Wrap the zone in ZoneResource
        return new ZoneResource($zone);
    }

    // Update the specified zone
    public function update(Request $request, $regionId, $id)
    {
        $region = Region::findOrFail($regionId);
        $zone = $region->zones()->findOrFail($id);

        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $zone->update([
            'name' => $request->name,
        ]);

        // Wrap the updated zone in ZoneResource
        return new ZoneResource($zone);
    }

    // Remove the specified zone
    public function destroy($regionId, $id)
    {
        $region = Region::findOrFail($regionId);
        $zone = $region->zones()->findOrFail($id);
        $zone->delete();

        // Return a success response
        return response()->json(['message' => 'Zone deleted successfully'], 204);
    }
}
