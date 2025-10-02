<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserManagementRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'Message' => 'User Successfully Showed',
                'data' => User::all(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserManagementRequest $request, User $management)
    {
        try {
            if($management){
                $validated = $request->safe()->all();
                $management->update(
                    ['role' => $validated['role']]);
                return response()->json([
                    'Message' => 'User Successfully Updated',
                    'data' => $management,
                ], 201);    
            }else{
                return response()->json([
                    'Message' => "Gagal",
                    'data' => null,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**p
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
