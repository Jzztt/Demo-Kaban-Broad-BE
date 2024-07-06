<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Register API (POST)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['success' => true, 'message' => 'Registration Successful', 'token' => $token], 200);
    }

    /**
     * Login API (POST)
     *
     * @param Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse The JSON response with the access token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $accessToken = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'data' => [
                    'access_token' => $accessToken,
                ],
                'message' => 'Login successful',
                'success' => true,
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated user's profile
     *
     * @param Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse The JSON response with the user's profile
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Profile retrieved successfully'
        ], 200);
    }

    /**
     * Logout API (POST)
     *
     * @param Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Successfully logged out'], 200);
    }
}
