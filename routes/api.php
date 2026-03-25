<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminAuthController;

Route::post('/contact', [ContactController::class, 'store']);
Route::options('{any}', function () {
    return response()->noContent(204)->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
        'Access-Control-Allow-Credentials' => 'false',
    ]);
})->where('any', '.*');
Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/admin/send-otp', [AdminAuthController::class, 'sendOtp']);
Route::post('/admin/verify-otp', [AdminAuthController::class, 'verifyOtp']);