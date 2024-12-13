<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    // Constructor to handle authorization if needed (e.g., Sanctum or Passport)
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Example middleware for API authentication
    }

    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start building the query
        $query = User::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by region ID
        if ($request->has('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // Filter by woreda ID
        if ($request->has('woreda_id')) {
            $query->where('woreda_id', $request->woreda_id);
        }

        // Filter by role
        if ($request->has('role')) {
            $role = $request->role;
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->get();

        // Return the users as a collection of resources
        return UserResource::collection($users);
    }


    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Custom validation using Validator
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'phone_number' => 'required|string',
        'status' => 'required|string',
        'woreda_id' => 'exists:woredas,id',
        'region_id' => 'exists:regions,id',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Use Hash::make() for hashing the password
        'phone_number' => $request->phone_number,
        'status' => $request->status,
        'woreda_id' => $request->woreda_id,
        'region_id' => $request->region_id,
    ]);

    // Return the newly created user wrapped in the UserResource
    return new UserResource($user);
}

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user); // Return the specified user wrapped in the resource
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
{
    // Custom validation using Validator
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:8',
        'phone_number' => 'sometimes|string',
        'status' => 'sometimes|string',
        'woreda_id' => 'sometimes|exists:woredas,id',
        'region_id' => 'sometimes|exists:regions,id',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Update the user with the validated data
    $user->update($request->only([
        'name', 'email', 'phone_number', 'status', 'woreda_id', 'region_id'
    ]));

    // If password is provided, update it after hashing it
    if ($request->has('password')) {
        $user->password = Hash::make($request->password); // Use Hash::make() for password hashing
        $user->save();
    }

    // Return the updated user wrapped in the UserResource
    return new UserResource($user);
}

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204); // Return no content response for successful deletion
    }

    public function showUsersByRegion(Request $request, $regionId)
{
    // Start building the query for users based on region_id
    $query = User::where('region_id', $regionId)
                 ->with(['roles', 'permissions', 'woreda.zone', 'region']);

    // If woreda_id is provided, filter by woreda_id as well
    if ($request->has('woreda_id')) {
        $query->where('woreda_id', $request->woreda_id);
    }

    // Get the filtered users
    $users = $query->get();

    // Return the users as a collection of resources
    return UserResource::collection($users);
}


    // Approve Supervisor account (set status from pending to approved)
    public function approveSupervisor($user_id)
    {
        $user = User::findOrFail($user_id);

        // Check if the user is a supervisor and is pending
        if (!$user->hasRole('supervisor')) {
            return response()->json(['message' => 'User is not a supervisor.'], 400);
        }

        if ($user->status !== USER::STATUS_PENDING) {
            return response()->json(['message' => 'Supervisor account is not pending.'], 400);
        }

        // Update status and assign the supervisor role
        $user->status = USER::STATUS_ACTIVE;
        $user->save();

        return response()->json(['message' => 'Supervisor approved successfully.']);
    }

    public function rejectSupervisor($user_id)
    {
        $user = User::findOrFail($user_id);

        // Check if the user is a supervisor and is pending
        if (!$user->hasRole('supervisor')) {
            return response()->json(['message' => 'User is not a supervisor.'], 400);
        }

        if ($user->status !== USER::STATUS_PENDING) {
            return response()->json(['message' => 'Supervisor account is not pending.'], 400);
        }

        // Update status and assign the supervisor role
        $user->status = USER::STATUS_REJECTED;
        $user->save();

        // $user->assignRole('supervisor');

        return response()->json(['message' => 'Supervisor rejected successfully.']);
    }

    // Suspend Supervisor account (set status to suspended)
    public function suspendSupervisor($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->hasRole('supervisor')) {
            return response()->json(['message' => 'User is not a supervisor.'], 400);
        }

        // Set status to suspended
        $user->status = USER::STATUS_SUSPENDED;
        $user->save();

        return response()->json(['message' => 'Supervisor suspended successfully.']);
    }

    // Approve Region Admin account (set status from pending to approved)
    public function approveRegionAdmin($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->hasRole('region_admin')) {
            return response()->json(['message' => 'User is not a Region Admin.'], 400);
        }

        if ($user->status !== USER::STATUS_PENDING) {
            return response()->json(['message' => 'Region Admin account is not pending.'], 400);
        }

        // Update status and assign the region admin role
        $user->status = USER::STATUS_ACTIVE;
        $user->save();

        // $user->assignRole('region_admin');

        return response()->json(['message' => 'Region Admin approved successfully.']);
    }

    public function rejectRegionAdmin($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->hasRole('region_admin')) {
            return response()->json(['message' => 'User is not a Region Admin.'], 400);
        }

        if ($user->status !== USER::STATUS_PENDING) {
            return response()->json(['message' => 'Region Admin account is not pending.'], 400);
        }

        // Update status and assign the region admin role
        $user->status = USER::STATUS_REJECTED;
        $user->save();

        // $user->assignRole('region_admin');

        return response()->json(['message' => 'Region Admin rejected successfully.']);
    }


    // Suspend Region Admin account (set status to suspended)
    public function suspendRegionAdmin($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->hasRole('region_admin')) {
            return response()->json(['message' => 'User is not a Region Admin.'], 400);
        }

        // Set status to suspended
        $user->status = USER::STATUS_SUSPENDED;
        $user->save();

        return response()->json(['message' => 'Region Admin suspended successfully.']);
    }

    

    // Show roles of a specific user
    public function showRoles($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json([
            'roles' => $user->getRoleNames()
        ]);
    }

    // Show permissions of a specific user
    public function showPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json([
            'permissions' => $user->getAllPermissions()
        ]);
    }
    public function updateUserStatus(Request $request, $userId)
{
    $validator = Validator::make($request->all(), [
        'status' => 'required|string|in:' . implode(',', [
            USER::STATUS_PENDING,
            USER::STATUS_ACTIVE,
            USER::STATUS_REJECTED,
            USER::STATUS_SUSPENDED
        ]),
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::findOrFail($userId);
    $user->status = $request->status;
    $user->save();

    return response()->json(['message' => 'User status updated successfully.']);
}
}
