<?php

namespace App\Http\Controllers\Api;

use App\Models\Household;
use App\Models\HouseholdStat;
use App\Http\Resources\HouseholdStatResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class HouseholdStatController extends Controller
{
    /**
     * Display a listing of household stats for a specific household.
     *
     * @param  int  $householdId
     * @return \Illuminate\Http\Response
     */
    public function index($householdId)
    {
        // Find the household
        $household = Household::findOrFail($householdId);

        // Get household stats associated with this household
        $householdStats = $household->householdStats;

        // Return household stats as a collection
        return HouseholdStatResource::collection($householdStats);
    }

    /**
     * Store a newly created household stat for a specific household.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $householdId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $householdId)
{
    // Validate the request data
    $validator = $this->validateHouseholdStatData($request);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Find the household
    $household = Household::findOrFail($householdId);

    // Create the household stat
    $householdStat = HouseholdStat::create([
        'no_of_active_cases' => $request->no_of_active_cases,
        'no_of_death' => $request->no_of_death,
        'no_of_people_at_risk' => $request->no_of_people_at_risk,
        'no_of_recovered' => $request->no_of_recovered,
        'date' => $request->date ?? now(),
        'household_id' => $household->id,
        'supervisor_id' =>auth()->id(),
    ]);

    // Return the created household stat as a resource
    return new HouseholdStatResource($householdStat);
}


    /**
     * Display the specified household stat.
     *
     * @param  int  $householdId
     * @param  int  $householdStatId
     * @return \Illuminate\Http\Response
     */
    public function show($householdId, $householdStatId)
    {
        // Find the household
        $household = Household::findOrFail($householdId);

        // Find the specific household stat associated with this household
        $householdStat = $household->householdStats()->findOrFail($householdStatId);

        // Return the household stat as a resource
        return new HouseholdStatResource($householdStat);
    }

    /**
     * Update the specified household stat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $householdId
     * @param  int  $householdStatId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $householdId, $householdStatId)
    {
        // Find the household
        $household = Household::findOrFail($householdId);

        // Find the specific household stat within the household
        $householdStat = $household->householdStats()->findOrFail($householdStatId);

        // Validate the incoming data
        $validator = $this->validateHouseholdStatData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the household stat
        $householdStat->update($request->all());

        // Return the updated household stat as a resource
        return new HouseholdStatResource($householdStat);
    }

    /**
     * Remove the specified household stat from the database.
     *
     * @param  int  $householdId
     * @param  int  $householdStatId
     * @return \Illuminate\Http\Response
     */
    public function destroy($householdId, $householdStatId)
    {
        // Find the household
        $household = Household::findOrFail($householdId);

        // Find the specific household stat within the household
        $householdStat = $household->householdStats()->findOrFail($householdStatId);

        // Delete the household stat
        $householdStat->delete();

        // Return a success response
        return response()->json(['message' => 'Household Stat deleted successfully.'], 200);
    }

    /**
     * Validate the incoming request data for household stat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateHouseholdStatData(Request $request)
    {
        return Validator::make($request->all(), [
            'no_of_active_cases' => 'required|integer',
            'no_of_death' => 'required|integer',
            'no_of_people_at_risk' => 'required|integer',
            'no_of_recovered' => 'required|integer',
            'date' => 'date',
            'supervisor_id' => 'exists:users,id',
        ]);
    }
}
