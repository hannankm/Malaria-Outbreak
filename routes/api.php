<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\WoredaController;
use App\Http\Controllers\Api\HouseholdController;
use App\Http\Controllers\Api\HouseholdStatController;
use App\Http\Controllers\Api\MalariaCaseController;
use App\Http\Controllers\Api\DashboardController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');
Route::resource('/regions', RegionController::class);
    Route::resource('/zones/{zoneId}/woredas', WoredaController::class);
    Route::resource('/regions/{regionId}/zones', ZoneController::class);

// ->middleware('api')->





// Super Admin route
Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    
    Route::resource('user', UserController::class);
});

// Region Admin route
Route::middleware(['auth:sanctum', 'role:region_admin', 'region_access:region_admin'])->group(function () {
    Route::resource('regions/{regionId}/zones', ZoneController::class);
    Route::get('/users/region/{regionId}', [UserController::class, 'showUsersByRegion']);
    Route::resource('zones/{zoneId}/woredas', WoredaController::class);
    // Route::resource('user', UserController::class);
});

// supervisor routes
Route::middleware(['auth:sanctum', 'region_access:supervisor'])->group(function () {
    Route::resource('woredas/{woredaId}/households', HouseholdController::class);
    Route::resource('household/{householdId}/household-stats', HouseholdStatController::class);
    Route::resource('household-stat/{householdStatId}/malaria-cases', MalariaCaseController::class);
});

// account approval by super admin
Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::put('/admin/region-admin/{user_id}/suspend', [UserController::class, 'suspendRegionAdmin']);
    Route::put('/admin/region-admin/{user_id}/approve', [UserController::class, 'approveRegionAdmin']);
    Route::put('/admin/region-admin/{user_id}/reject', [UserController::class, 'rejectRegionAdmin']);

});
// account approval by reg admin
Route::middleware(['auth:sanctum', 'role:region_admin|super_admin'])->group(function () {
    Route::put('/admin/supervisor/{user_id}/suspend', [UserController::class, 'suspendSupervisor']);
    Route::put('/admin/supervisor/{user_id}/approve', [UserController::class, 'approveSupervisor']);
    Route::put('/admin/supervisor/{user_id}/reject', [UserController::class, 'rejectSupervisor']);

});

