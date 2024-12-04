<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Woreda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WoredaResource;

class WoredaController extends Controller
{
    // Display a listing of the woredas for a specific zone
    public function index($zoneId)
    {
        $zone = Zone::findOrFail($zoneId);

        // Use WoredaResource for structured response
        return WoredaResource::collection($zone->woredas);
    }

    // Store a newly created woreda
    public function store(Request $request, $zoneId)
    {
        $zone = Zone::findOrFail($zoneId);

        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $woreda = $zone->woredas()->create([
            'name' => $request->name,
        ]);

        // Wrap the created woreda in WoredaResource
        return new WoredaResource($woreda);
    }

    // Display the specified woreda
    public function show($zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);

        // Wrap the woreda in WoredaResource
        return new WoredaResource($woreda);
    }

    // Update the specified woreda
    public function update(Request $request, $zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);

        // Use Validator facade for validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $woreda->update([
            'name' => $request->name,
        ]);

        // Wrap the updated woreda in WoredaResource
        return new WoredaResource($woreda);
    }

    // Remove the specified woreda
    public function destroy($zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);
        $woreda->delete();

        // Return a success response
        return response()->json(['message' => 'Woreda deleted successfully'], 204);
    }
}
