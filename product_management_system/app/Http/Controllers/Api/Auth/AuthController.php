<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use App\Http\Requests\AuthRegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'success' => true
            ], 201);

        } catch (\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed. Please check your input.',
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
           
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
           
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            
            return response()->json([
                'token' => $token,
                'success' => true
            ], 200);

        } catch (ValidationException $e) {
            
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            $user->tokens()->delete();

            return response()->json([
                'message' => 'Successfully logged out.',
                'success' => true
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
