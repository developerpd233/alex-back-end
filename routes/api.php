<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OTPController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AlarmController;
use App\Http\Controllers\API\UppController;


Route::get('allUsers', [AuthController::class, 'allUsers']);
Route::post('signup', [AuthController::class, 'signup']);
Route::post('user_exist', [AuthController::class, 'user_exist']);

Route::post('image_update', [ImageController::class, 'image_update']);
Route::post('image_upload', [ImageController::class, 'image_upload']);

Route::put('update_profile', [ProfileController::class, 'update_profile']);
Route::get('get_profile', [ProfileController::class, 'get_profile']);


Route::post('authOtp', [OTPController::class, 'authOtp']);
Route::post('verifyOtp', [OTPController::class, 'verifyOtp']);
Route::put('resendOTP', [OTPController::class, 'resendOTP']);

Route::post('setAlarm', [AlarmController::class, 'setAlarm']);
Route::get('getAlarm', [AlarmController::class, 'getAlarm']);
Route::get('AllRingtone', [AlarmController::class, 'AllRingtone']);
Route::put('update_alarm', [AlarmController::class, 'update_alarm']);
Route::delete('delete_alarm', [AlarmController::class, 'delete_alarm']);



Route::post('UppAlarm', [UppController::class, 'UppAlarm']);

Route::post('head', [AuthController::class, 'head']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});