<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminAuthController;

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/admin/send-otp', [AdminAuthController::class, 'sendOtp']);
Route::post('/admin/verify-otp', [AdminAuthController::class, 'verifyOtp']);