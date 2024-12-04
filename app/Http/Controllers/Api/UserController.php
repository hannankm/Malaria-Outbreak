<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
    public function index()
    {
        $users = User::all();  // You can use pagination here for large datasets: User::paginate(10);
        return UserResource::collection($users); // Return all users wrapped in the resource
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string',
            'status' => 'required|string',
            'woreda_id' => 'required|exists:woredas,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'woreda_id' => $request->woreda_id,
            'region_id' => $request->region_id,
        ]);

        return new UserResource($user); // Return the newly created user wrapped in the resource
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
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'phone_number' => 'sometimes|string',
            'status' => 'sometimes|string',
            'woreda_id' => 'sometimes|exists:woredas,id',
            'region_id' => 'sometimes|exists:regions,id',
        ]);

        $user->update($request->only([
            'name', 'email', 'phone_number', 'status', 'woreda_id', 'region_id'
        ]));

        if ($request->password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return new UserResource($user); // Return the updated user wrapped in the resource
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
}
