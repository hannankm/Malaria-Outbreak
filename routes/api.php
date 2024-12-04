<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HiController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\HouseholdController;
use App\Http\Controllers\Api\HouseholdStatController;
use App\Http\Controllers\Api\MalariaCaseController;



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


Route::resource('regions', RegionController::class);
Route::resource('regions/{regionId}/zones', ZoneController::class);
Route::resource('zones/{zoneId}/woredas', WoredaController::class);
Route::resource('woreda/{woredaId}/households', HouseholdController::class);
Route::resource('household/{householdId}/household-stats', HouseholdStatController::class);
Route::resource('household-stat/{householdStatId}/malaria-cases', MalariaCaseController::class);
