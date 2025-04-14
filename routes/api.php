<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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


Route::group(['prefix' => 'v1'], function () {
    
    Route::post('auth', [AuthController::class, 'auth']);

    Route::middleware('auth:api')->group(function () {

        Route::get('dashboard/summary', [DashboardController::class, 'summary']);

        Route::post('logout', [AuthController::class, 'logout']);

        Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update_status');

        Route::apiResource('users',UserController::class);
    
    });

});
