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
    // Define the validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        'phone_number' => 'required|string|max:20',
        'woreda_id' => 'uuid|exists:woredas,id',
        'region_id' => 'uuid|exists:regions,id',
        'role' => 'required|string',
    ];

    // Define custom error messages (optional)
    $messages = [
        'email.unique' => 'This email is already registered.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];

    // Create the validator instance
    $validator = Validator::make($request->all(), $rules, $messages);

    // Check if the validation passes
    if ($validator->fails()) {
        // Return the validation errors if validation fails
        return response()->json([
            'errors' => $validator->errors()
        ], 422);  // 422 Unprocessable Entity
    }

    // Proceed with registration if validation passes
    try {
        // Create the user with the provided data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'status' => User::STATUS_PENDING, // Default status as 'pending'
            'woreda_id' => $request->woreda_id ?? null,
            'region_id' => $request->region_id ?? null,
        ]);
        // supervisor or region_admin


        // Assign the role to the user
        $role = Role::findByName($request->role, 'api');
        $user->assignRole($role);

        // Return a successful response
        return response()->json(['message' => 'User registered successfully. Your account is under review.'], 201);

    } catch (\Exception $e) {
        // Handle any exceptions or errors
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

        // Create and return the API token
        $token = $user->createToken('MalariaApp')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->makeHidden(['roles']),
            'role' => $user->getRoleNames(), // This will return the roles assigned to the user
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
