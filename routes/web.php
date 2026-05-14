<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route to get env config for frontend
Route::get('/api-config', function() {
    return response()->json([
        'api_url' => env('API_URL', 'http://127.0.0.1:8000/api'),
        'app_url' => env('APP_URL', 'http://127.0.0.1:8000'),
    ]);
});