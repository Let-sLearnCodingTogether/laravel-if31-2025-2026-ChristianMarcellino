<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        try {
            $validated = $request->safe()->all();
            if(!Auth::attempt($validated)){
                return response()->json([
                'message' => 'Provided credentials are not registered!'
            ], 401);
            }

            $user = $request->user();
            $token = $user->createToken('api-token', ['*'])->plainTextToken;
            return response()->json([
                'message' => 'Login Successful!',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login Failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(RegisterRequest $request){
        try {
            $validated = $request->safe()->all();
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            return response()->json([
                'message' => 'User Successfully Registered!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration Failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Successfully Logout',
                'data' => 'Test'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => 'Test'
            ], 500);
        }
    }
}
