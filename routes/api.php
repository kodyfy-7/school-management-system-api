<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

require __DIR__.'/api/v1.php';

// Route::post('login', [AuthController::class, 'login']);
// Route::post('login/admin', [AuthController::class, 'adminLogin']);
// Route::post('register', AuthController::class, 'register');