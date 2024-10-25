<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DirectorController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\RentalController;


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('/user', function (Request $request) {
    return $request->user()->load('roles');
})->middleware('auth:sanctum');

Route::post('/rentals', [RentalController::class, 'rentMovie'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('customers', CustomerController::class)->missing(function (Request $request) {
        $response = [
            'success' => false,
            'message' => 'Customer not found.'
        ];
        
        return response()->json($response, 404);
    });
    Route::apiResource('directors', DirectorController::class)->missing(function (Request $request) {
        $response = [
            'success' => false,
            'message' => 'Director not found.'
        ];
        
        return response()->json($response, 404);
    });
    Route::apiResource('movies', MovieController::class)->missing(function (Request $request) {
        $response = [
            'success' => false,
            'message' => 'Movie not found.'
        ];
        
        return response()->json($response, 404);
    });
});
