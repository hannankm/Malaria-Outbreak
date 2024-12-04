<?php

namespace App\Http\Controllers\Api;

use App\Models\HouseholdStat;
use App\Models\MalariaCase;
use App\Http\Resources\MalariaCaseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MalariaCaseController extends Controller
{
    /**
     * Display a listing of malaria cases for a specific household stat.
     *
     * @param  int  $householdStatId
     * @return \Illuminate\Http\Response
     */
    public function index($householdStatId)
    {
        // Find the household stat
        $householdStat = HouseholdStat::findOrFail($householdStatId);

        // Get malaria cases associated with this household stat
        $malariaCases = $householdStat->malariaCases;

        // Return malaria cases as a collection
        return MalariaCaseResource::collection($malariaCases);
    }

    /**
     * Store a newly created malaria case for a specific household stat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $householdStatId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $householdStatId)
    {
        // Validate the request data
        $validator = $this->validateMalariaCaseData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the household stat
        $householdStat = HouseholdStat::findOrFail($householdStatId);

        // Create the malaria case
        $malariaCase = MalariaCase::create([
            'status' => $request->status,
            'age_group' => $request->age_group,
            'sex' => $request->sex,
            'diagnosed' => $request->diagnosed,
            'household_stat_id' => $householdStat->id,
        ]);

        // Return the created malaria case as a resource
        return new MalariaCaseResource($malariaCase);
    }

    /**
     * Display the specified malaria case.
     *
     * @param  int  $householdStatId
     * @param  int  $malariaCaseId
     * @return \Illuminate\Http\Response
     */
    public function show($householdStatId, $malariaCaseId)
    {
        // Find the household stat
        $householdStat = HouseholdStat::findOrFail($householdStatId);

        // Find the specific malaria case associated with this household stat
        $malariaCase = $householdStat->malariaCases()->findOrFail($malariaCaseId);

        // Return the malaria case as a resource
        return new MalariaCaseResource($malariaCase);
    }

    /**
     * Update the specified malaria case.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $householdStatId
     * @param  int  $malariaCaseId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $householdStatId, $malariaCaseId)
    {
        // Find the household stat
        $householdStat = HouseholdStat::findOrFail($householdStatId);

        // Find the specific malaria case within the household stat
        $malariaCase = $householdStat->malariaCases()->findOrFail($malariaCaseId);

        // Validate the incoming data
        $validator = $this->validateMalariaCaseData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the malaria case
        $malariaCase->update($request->all());

        // Return the updated malaria case as a resource
        return new MalariaCaseResource($malariaCase);
    }

    /**
     * Remove the specified malaria case from the database.
     *
     * @param  int  $householdStatId
     * @param  int  $malariaCaseId
     * @return \Illuminate\Http\Response
     */
    public function destroy($householdStatId, $malariaCaseId)
    {
        // Find the household stat
        $householdStat = HouseholdStat::findOrFail($householdStatId);

        // Find the specific malaria case associated with the household stat
        $malariaCase = $householdStat->malariaCases()->findOrFail($malariaCaseId);

        // Delete the malaria case
        $malariaCase->delete();

        // Return a success response
        return response()->json(['message' => 'Malaria Case deleted successfully.'], 200);
    }

    /**
     * Validate the incoming request data for malaria case.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateMalariaCaseData(Request $request)
    {
        return Validator::make($request->all(), [
            'status' => 'required|string',
            'age_group' => 'required|string',
            'sex' => 'required|string',
            'diagnosed' => 'required|date',
            'household_stat_id' => 'required|exists:household_stats,id',  // Ensure this belongs to a valid HouseholdStat
        ]);
    }
}
