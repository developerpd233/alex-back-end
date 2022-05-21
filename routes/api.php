<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OTPController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\ProfileController;


Route::post('signup', [AuthController::class, 'signup']);
Route::post('signin', [AuthController::class, 'signin']);
Route::post('user_exist', [AuthController::class, 'user_exist']);

Route::post('image_update', [ImageController::class, 'image_update']);
Route::post('image_upload', [ImageController::class, 'image_upload']);

Route::post('update_profile', [ProfileController::class, 'update_profile']);
Route::post('get_profile', [ProfileController::class, 'get_profile']);


Route::post('authOtp', [OTPController::class, 'authOtp']);
Route::post('verifyOtp', [OTPController::class, 'verifyOtp']);
Route::post('resendOTP', [OTPController::class, 'resendOTP']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
