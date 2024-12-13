<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\MalariaCase;
use App\Models\HouseholdStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Assuming the user has a role or permission system to differentiate region and super admin
        if (auth()->user()->hasRole('super_admin')) {
            return $this->superAdminDashboard($request);
        }

        // Region Dashboard logic
        return $this->regionDashboard();
    }

    // Super Admin Dashboard Method
    public function superAdminDashboard(Request $request)
    {
        $regionId = $request->get('region_id'); // Optional filter for a specific region

        // Summation Data for Super Admin (All regions or specified region)
        $summations = [
            'deaths' => $this->getSummationData('death', $regionId),
            'active_cases' => $this->getSummationData('active', $regionId),
            'new_cases' => $this->getSummationData('new', $regionId),
            'recoveries' => $this->getSummationData('recovered', $regionId),
            'at_risk' => $this->getSummationData('at risk', $regionId),
        ];

        // Count the number of households (for all regions or a specific region)
        $householdCount = HouseholdStat::when($regionId, function ($query) use ($regionId) {
            return $query->whereHas('household.woreda.zone.region', function ($query) use ($regionId) {
                $query->where('region_id', $regionId);
            });
        })
        ->distinct()->count('household_id');

        // Graph Data (diagnosed, age_group, sex)
        $graphData = [
            'by_diagnosed' => $this->getGraphDataByStatus('diagnosed', $regionId),
            'by_age_group' => $this->getGraphDataByStatus('age_group', $regionId),
            'by_gender' => $this->getGraphDataByStatus('sex', $regionId),
        ];

        return response()->json([
            'summations' => $summations,
            'household_count' => $householdCount,
            'graph_data' => $graphData,
        ]);
    }

    // Region Dashboard Method
    public function regionDashboard()
    {
        $user = auth()->user();
        $regionId = $user->region_id; // Assuming the user has a region_id attribute

        // Get malaria case counts for the last 30 days
        $dateFrom = now()->subDays(30);

        // Summation Data for Region
        $summations = [
            'deaths' => MalariaCase::where('status', 'death')
                ->where('created_at', '>=', $dateFrom)
                ->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                })
                ->count(),

            'active_cases' => MalariaCase::where('status', 'active')
                ->where('created_at', '>=', $dateFrom)
                ->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                })
                ->count(),

            'new_cases' => MalariaCase::where('status', 'new')
                ->where('created_at', '>=', $dateFrom)
                ->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                })
                ->count(),

            'recoveries' => MalariaCase::where('status', 'recovery')
                ->where('created_at', '>=', $dateFrom)
                ->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                })
                ->count(),

            'at_risk' => MalariaCase::where('status', 'at risk')
                ->where('created_at', '>=', $dateFrom)
                ->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                })
                ->count(),
        ];

        // Count the number of households in the region
        $householdCount = HouseholdStat::whereHas('household.woreda.zone.region', function ($query) use ($regionId) {
            $query->where('region_id', $regionId);
        })->distinct()->count('household_id');

        // Graph Data (diagnosed, age_group, sex)
        $graphData = [
            'by_diagnosed' => $this->getGraphDataByStatus('diagnosed', $regionId),
            'by_age_group' => $this->getGraphDataByStatus('age_group', $regionId),
            'by_gender' => $this->getGraphDataByStatus('sex', $regionId),
        ];

        return response()->json([
            'summations' => $summations,
            'household_count' => $householdCount,
            'graph_data' => $graphData,
        ]);
    }

    // Helper method to get graph data by field (diagnosed, age_group, sex)
    private function getGraphDataByStatus($field, $regionId = null)
    {
        return MalariaCase::select($field, DB::raw('count(*) as total'))
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                });
            })
            ->groupBy($field)
            ->get();
    }

    // Helper method to get summation data (death, active, new, recovery, at risk)
    private function getSummationData($status, $regionId = null)
    {
        return MalariaCase::where('status', $status)
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('household_stat.household.woreda.zone.region', function ($query) use ($regionId) {
                    $query->where('region_id', $regionId);
                });
            })
            ->count();
    }
}
