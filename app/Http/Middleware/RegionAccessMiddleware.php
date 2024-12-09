<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Role checks
        if ($role === 'region_admin') {
            // Check if user is a region admin
            if ($user->hasRole('region_admin')) {
                // Ensure they can only access data for their specific region
                $regionId = $user->region_id; // Assuming user has a region_id field
                $routeRegionId = $request->route('regionId');  // Get regionId from the route
    
                // If route has regionId, check it
                if ($routeRegionId && $routeRegionId != $regionId) {
                    return response()->json(['error' => 'Forbidden: You can only access your own region data'], 403);
                }
    
            //     // Check for zoneId, woredaId, and householdId in similar fashion
            //     $routeZoneId = $request->route('zoneId');
            //     $routeWoredaId = $request->route('woredaId');
            //     $routeHouseholdId = $request->route('householdId');
    
            //     // If route has zoneId, check it
            //     if ($routeZoneId) { 
            //         // find region id of zone and check with user region id
            //         if ($user->region_id != $routeZoneId)
            //      {
            //         return response()->json(['error' => 'Forbidden: You can only access data within your region\'s zones'], 403);
            //     }
            // }
            //     // If route has woredaId, check it
            //     if ($routeWoredaId) { 
            //         // find woreda zone id then zone's region id and check with user region id
            //         if( $user->region_id != $request->route('woredaId')) {
            //         return response()->json(['error' => 'Forbidden: You can only access data within your region\'s woredas'], 403);
            //     }
            // }
            //     // If route has householdId, check it
            //     if ($routeHouseholdId && $user->region_id != $request->route('householdId')) {
            //         return response()->json(['error' => 'Forbidden: You can only access your own region\'s households'], 403);
            //     }
    
            } else {
                return response()->json(['error' => 'Forbidden: You do not have access to this resource'], 403);
            }
    
        } elseif ($role === 'supervisor') {
            // Check if user is a supervisor
            if ($user->hasRole('supervisor')) {
                // Ensure they can only access data for their woreda
                $woredaId = $user->woreda_id; // Assuming user has a woreda_id field
                $routeWoredaId = $request->route('woredaId');  // Get woredaId from the route
    
                // If route has woredaId, check it
                if ($routeWoredaId && $routeWoredaId != $woredaId) {
                    return response()->json(['error' => 'Forbidden: You can only access your own woreda data'], 403);
                }
    
                // // Check for householdId in similar fashion
                // $routeHouseholdId = $request->route('householdId');
                // if ($routeHouseholdId && $user->woreda_id != $request->route('householdId')) {
                //     return response()->json(['error' => 'Forbidden: You can only access your own woreda\'s households'], 403);
                // }
    
            } else {
                return response()->json(['error' => 'Forbidden: You do not have access to this resource'], 403);
            }
    
        } elseif ($role === 'super_admin') {
            // Super Admin can access all data
            if (!$user->hasRole('super_admin')) {
                return response()->json(['error' => 'Forbidden: You do not have access to this resource'], 403);
            }
        }
    
        // Continue to the next request if checks pass
        return $next($request);
    }
}
