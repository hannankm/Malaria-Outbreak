<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;





class AuthController extends Controller
{
    //


    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|same:password',
            'phone_number' => 'required|string|max:20',
            'region_id' => 'required|uuid|exists:regions,id',
            'role' => 'required|string',
        ];
    
        // Additional rules for supervisors
        if ($request->role === 'supervisor') {
            $rules['woreda_id'] = 'required|uuid|exists:woredas,id';
        } else {
            $rules['woreda_id'] = 'nullable|uuid|exists:woredas,id';
        }
    
        // Custom error messages
        $messages = [
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'woreda_id.required' => 'Woreda is required for supervisors.',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            // Return validation errors
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            // Proceed with user creation
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'status' => User::STATUS_PENDING, // Default status as 'pending'
                'woreda_id' => $request->woreda_id ?? null,
                'region_id' => $request->region_id ?? null,
            ]);
    
            // Assign the role to the user
            $role = Role::findByName($request->role, 'api');
            $user->assignRole($role);
    
            // Return success response
            return response()->json(['message' => 'User registered successfully. Your account is under review.'], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Registration failed. ' . $e->getMessage()], 500);
        }
    }
    
public function login(Request $request)
{
    // Create a validator instance for the login request
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'password' => 'required|string|min:8',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Check if the user exists
    $user = User::where('email', $request->email)->first();

    // If user doesn't exist or password is incorrect
    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Check the user's status before proceeding with login
    if ($user->status === 'pending') {
        // Return a response if the account is pending
        return response()->json([
            'message' => 'Your account is under review. Please wait until it is approved.',
        ], 403);
    }

    if ($user->status === 'suspended') {
        // Return a response if the account is suspended
        return response()->json([
            'message' => 'Your account has been suspended. Contact support for more information.',
        ], 403);
    }

    if ($user->status !== 'active') {
        // If account is neither active, pending, nor suspended, return an appropriate message
        return response()->json([
            'message' => 'Your account is not active. Please contact support.',
        ], 403);
    }

    // Create the API token if status is active
    $token = $user->createToken('MalariaApp')->plainTextToken;

    // Return the successful response with token and user info
    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        // 'user' => $user->makeHidden(['roles']), // Hide roles in the response
        'user' => $user, // Hide roles in the response
        'role' => $user->getRoleNames(), // Get and return the roles assigned to the user
    ], 200);
}


    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }
    
}
