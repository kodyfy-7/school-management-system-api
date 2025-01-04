<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['accept-json']], function () {
    Route::group(['prefix' => 'auth'], function () {
        // Route::get('/loginx', [AuthController::class, 'testDevice']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });
    
});
