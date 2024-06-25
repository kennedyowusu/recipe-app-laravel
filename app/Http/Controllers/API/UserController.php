<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Registers a new user
    public function registerUser(UserRequest $request)
    {
        // Validate request data
        $validatedAttribute = $request->validated();

        // Create a new user with the validated data
        $user = User::create([
            'name' => $validatedAttribute['name'],
            'email' => $validatedAttribute['email'],
            'username' => $validatedAttribute['username'],
            'password' => Hash::make($validatedAttribute['password']),
        ]);

        // Generate an authentication token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a success response with user and token
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);  // Use 201 status code for resource creation
    }

    // Logs in a user
    public function loginUser(Request $request)
    {
        // Validate request data
        $validatedAttribute = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user with the provided credentials
        if (! Auth::attempt(['email' => $validatedAttribute['email'], 'password' => $validatedAttribute['password']])) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Generate a token for the authenticated user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a success response with the user and token
        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logs out a user by deleting their current access token
    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // Return a success response for logout
        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

    // Gets the authenticated user's information
    public function getUser(Request $request)
    {
        // Return the authenticated user
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
