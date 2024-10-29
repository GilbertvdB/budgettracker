<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiLoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {   
        info('authenticating...');
        dd($request->all());
        
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // If validation fails, return a 422 response with validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user with the provided credentials
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            // Return a successful JSON response, e.g., user data or token
            return response()->json([
                'status' => 'success',
                'message' => 'Authentication successful',
                'user' => Auth::user(),  // or use a token if using Laravel Passport, Sanctum, etc.
            ], 200);
        }

        // If authentication fails, return a 401 response
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }
}
