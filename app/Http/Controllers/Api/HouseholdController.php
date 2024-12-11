<?php

namespace App\Http\Controllers\Api;

use App\Models\Household;
use App\Models\Woreda;
use App\Http\Resources\HouseholdResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class HouseholdController extends Controller
{
    /**
     * Display a listing of households for a specific woreda.
     *
     * @param  int  $woredaId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $woredaId)
{
    // Find the woreda
    $woreda = Woreda::findOrFail($woredaId);

    // Initialize query builder for households
    $query = $woreda->households();

    // Check if there's a search query in the request
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;

        // Apply search filters, assuming you're searching on name, house number, or location
        $query->where(function ($q) use ($searchTerm) {
            $q->where('house_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('full_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('location', 'like', '%' . $searchTerm . '%');
        });
    }

    // Get filtered households
    $households = $query->get();

    // Return households as a collection
    return HouseholdResource::collection($households);
}


    /**
     * Store a newly created household for a specific woreda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $woredaId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $woredaId)
    {
        // Validate the request data
        $validator = $this->validateHouseholdData($request);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the woreda
        $woreda = Woreda::findOrFail($woredaId);

        // Create the household
        $household = Household::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'house_number' => $request->house_number,
            'spouse_name' => $request->spouse_name,
            'spouse_phone_number' => $request->spouse_phone_number,
            'no_of_adults' => $request->no_of_adults,
            'no_of_children' => $request->no_of_children,
            'location' => $request->location,
            'supervisor_id' => $request->supervisor_id,
            'woreda_id' => $woreda->id, // Assign the woreda ID
        ]);

        // Return the created household resource
        return new HouseholdResource($household);
    }

    /**
     * Display the specified household.
     *
     * @param  int  $woredaId
     * @param  int  $householdId
     * @return \Illuminate\Http\Response
     */
    public function show($woredaId, $householdId)
    {
        // Find the woreda
        $woreda = Woreda::findOrFail($woredaId);

        // Find the specific household within the woreda
        $household = $woreda->households()->findOrFail($householdId);

        // Return the household as a resource
        return new HouseholdResource($household);
    }

    /**
     * Update the specified household.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $woredaId
     * @param  int  $householdId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $woredaId, $householdId)
    {
        // Find the woreda
        $woreda = Woreda::findOrFail($woredaId);

        // Find the specific household within the woreda
        $household = $woreda->households()->findOrFail($householdId);

        // Validate the incoming data
        $validator = $this->validateHouseholdData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the household
        $household->update($request->all());

        // Return the updated household as a resource
        return new HouseholdResource($household);
    }

    /**
     * Remove the specified household from the database.
     *
     * @param  int  $woredaId
     * @param  int  $householdId
     * @return \Illuminate\Http\Response
     */
    public function destroy($woredaId, $householdId)
    {
        // Find the woreda
        $woreda = Woreda::findOrFail($woredaId);

        // Find the specific household within the woreda
        $household = $woreda->households()->findOrFail($householdId);

        // Delete the household
        $household->delete();

        // Return a success response
        return response()->json(['message' => 'Household deleted successfully.'], 200);
    }

    /**
     * Validate the incoming request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateHouseholdData(Request $request)
    {
        return Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'house_number' => 'required|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'spouse_phone_number' => 'nullable|string|max:15',
            'no_of_adults' => 'required|integer',
            'no_of_children' => 'required|integer',
            'location' => 'required|string|max:255',
            'supervisor_id' => 'required|exists:users,id',
        ]);
    }
}
