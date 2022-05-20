<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OTPController;


Route::post('signup', [AuthController::class, 'signup']);
Route::post('signin', [AuthController::class, 'signin']);
Route::post('user_exist', [AuthController::class, 'user_exist']);

Route::post('authOtp', [AuthController::class, 'authOtp']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
