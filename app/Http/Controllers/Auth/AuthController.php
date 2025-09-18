<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        try {
            $validated = $request->safe()->all();
            $user = User::where($validated['email'], )
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function register(RegisterRequest $request){
        try {
            $validated = $request->safe()->all();
            $validated['password'] = Hash::make($validated['password']);
            User::create($validated);
            return response()->json([
                'message' => 'User Successfully Registered!',
                'user' => $validated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration Failed!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout(){
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
