<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function() {
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user', [AuthController::class, 'user']);

        // Tasks APIs
        Route::prefix('/tasks')->group(function() {
            Route::get('/', [TaskController::class, 'index']);
            Route::post('/save', [TaskController::class, 'save']);
            Route::post('/completed', [TaskController::class, 'completed']);
        });

    });
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

});