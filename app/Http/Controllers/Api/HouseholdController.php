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
            'supervisor_id' =>auth()->id(),
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
    // public function show($id)
    // {
    //     // Step 1: Retrieve the household data along with stats and malaria cases
    //     $household = Household::with(['householdStats.malariaCases'])
    //         ->where('id', $id)
    //         ->firstOrFail();

    //     // Step 2: Transform the household stats to include grouped malaria cases
    //     $householdStats = $household->householdStats->map(function ($stat) {
    //         return [
    //             'id' => $stat->id,
    //             'no_of_active_cases' => $stat->no_of_active_cases,
    //             'no_of_death' => $stat->no_of_death,
    //             'no_of_people_at_risk' => $stat->no_of_people_at_risk,
    //             'no_of_recovered' => $stat->no_of_recovered,
    //             'date' => $stat->date,
    //             'malaria_cases' => $this->groupMalariaCases($stat->malariaCases),
    //         ];
    //     });

    //     // Step 3: Build the response
    //     $response = [
    //         'id' => $household->id,
    //         'full_name' => $household->full_name,
    //         'phone_number' => $household->phone_number,
    //         'house_number' => $household->house_number,
    //         'spouse_name' => $household->spouse_name,
    //         'spouse_phone_number' => $household->spouse_phone_number,
    //         'no_of_adults' => $household->no_of_adults,
    //         'no_of_children' => $household->no_of_children,
    //         'location' => $household->location,
    //         'supervisor' => $household->supervisor,
    //         'woreda' => [
    //             'id' => $household->woreda->id,
    //             'name' => $household->woreda->name,
    //             'zone_id' => $household->woreda->zone_id,
    //         ],
    //         'household_stats' => $householdStats,
    //     ];

    //     return response()->json(['data' => $response]);
    // }

    // /**
    //  * Group malaria cases by their status.
    //  */
    // private function groupMalariaCases($malariaCases)
    // {
    //     return $malariaCases->groupBy('status')->map(function ($cases, $status) {
    //         return $cases->map(function ($case) {
    //             return [
    //                 'id' => $case->id,
    //                 'age_group' => $case->age_group,
    //                 'sex' => $case->sex,
    //                 'diagnosed' => $case->diagnosed,
    //             ];
    //         });
    //     });
    // }
    

    public function show($woredaId, $householdId)
    {
        // Find the woreda
        $woreda = Woreda::findOrFail($woredaId);
    
        // Find the specific household and load stats with malaria cases grouped by status
        $household = $woreda->households()
                            ->with(['householdStats.malariaCases' => function ($query) {
                                $query->orderBy('status'); // Optional: Order cases by status
                            }])
                            ->findOrFail($householdId);
    
        // Group malaria cases by status in stats after fetching
        $household->householdStats->each(function ($stat) {
            $stat->grouped_malaria_cases = $stat->malariaCases->groupBy('status');
        });
    
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
        ]);
    }
}
