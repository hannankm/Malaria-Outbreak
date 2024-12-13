<?php
namespace App\Http\Controllers\Api;

use App\Models\Zone;
use App\Models\Woreda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WoredaResource;
use App\Http\Controllers\Controller;

class WoredaController extends Controller
{
    // Display a dynamically filtered list of woredas for a specific zone
    public function index(Request $request, $zoneId)
    {
        $zone = Zone::findOrFail($zoneId);

        // Handle the search query
        $search = $request->query('search', '');

        // Dynamically filter woredas by name using 'LIKE' clause
        $woredasQuery = $zone->woredas();
        if ($search) {
            $woredasQuery->where('name', 'like', "%$search%");
        }

        // Respond with filtered woredas
        return WoredaResource::collection($woredasQuery->get());
    }

    // Store a newly created woreda
    public function store(Request $request, $zoneId)
    {
        $zone = Zone::findOrFail($zoneId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $woreda = $zone->woredas()->create([
            'name' => $request->name,
        ]);

        return new WoredaResource($woreda);
    }

    public function show($zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);

        return new WoredaResource($woreda);
    }

    public function update(Request $request, $zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $woreda->update([
            'name' => $request->name,
        ]);

        return new WoredaResource($woreda);
    }

    public function destroy($zoneId, $id)
    {
        $zone = Zone::findOrFail($zoneId);
        $woreda = $zone->woredas()->findOrFail($id);
        $woreda->delete();

        return response()->json(['message' => 'Woreda deleted successfully'], 204);
    }
}
