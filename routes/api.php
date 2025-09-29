<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\UserManagementController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/category', function (Request $request) {
    return Category::all();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('register',[AuthController::class, 'register']);   
    Route::post('login',[AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::get('spot/review/{spot}',[SpotController::class, 'review']);
    Route::post('logout',[AuthController::class, 'logout']);
    Route::apiResource('spot', SpotController::class);
    Route::apiResource('review', ReviewController::class)
    ->only([
        'index',
        'store',
        'destroy'
    ])
    ->middlewareFor(['store', 'index'], 'ensureUserHasRole:user')
    ->middlewareFor(['destroy'], 'ensureUserHasRole:admin');
    
    Route::apiResource('usermanagement', UserManagementController::class)
    ->only([
        'index',
        'update'
    ])
    ->middlewareFor(['index','update'], 'ensureUserHasRole:admin');
    
});

