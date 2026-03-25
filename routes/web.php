<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;



Route::get('/', function () {
    return view('welcome');
});

// Allow frontend calls to `/api/contact` even if `routes/api.php` is mounted differently.
// This prevents 404 on contact form submission.
Route::options('/api/contact', function () {
    return response()->noContent(204)->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
        'Access-Control-Allow-Credentials' => 'false',
    ]);
});

Route::post('/api/contact', [ContactController::class, 'store']);

Route::get('/api/contacts', [ContactController::class, 'index']);
