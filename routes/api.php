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


Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/dropdown', [RegionController::class, 'dropdown']);



Route::resource('regions', RegionController::class);
Route::resource('regions/{regionId}/zones', ZoneController::class);
Route::resource('zones/{zoneId}/woredas', WoredaController::class);
Route::resource('woredas/{woredaId}/households', HouseholdController::class);
Route::resource('household/{householdId}/household-stats', HouseholdStatController::class);
Route::resource('household-stat/{householdStatId}/malaria-cases', MalariaCaseController::class);

Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::get('/households', [HouseholdController::class, 'fetchAllHouseholds']);

});
Route::middleware(['auth:sanctum', 'role:region_admin'])->group(function () {
    Route::get('/households/by-region', [HouseholdController::class, 'fetchHouseholdsByRegion']);


}); 
Route::get('/households/{householdId}', [HouseholdController::class, 'showHousehold']);



// Super Admin route
Route::middleware(['auth:sanctum', 'role:super_admin|region_admin'])->group(function () {
    Route::resource('/users', UserController::class);
    Route::patch('/users/{userId}/status', [UserController::class, 'updateUserStatus']);

});

// Region Admin route
Route::middleware(['auth:sanctum', 'role:region_admin', 'region_access:region_admin'])->group(function () {
    Route::get('/users/region/{regionId}', [UserController::class, 'showUsersByRegion']);
    

});

// supervisor routes
Route::middleware(['auth:sanctum' ])->group(function () {
    Route::resource('woredas/{woredaId}/households', HouseholdController::class);
    Route::resource('household/{householdId}/household-stats', HouseholdStatController::class);
    Route::resource('household-stat/{householdStatId}/malaria-cases', MalariaCaseController::class);
});



