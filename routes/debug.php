<?php

use Illuminate\Support\Facades\Route;

// Debug route untuk melihat error Google OAuth
Route::get('/debug-oauth', function () {
    return view('auth.google-error', [
        'error' => 'Test error message',
        'details' => 'This is a test error page',
        'file' => '/path/to/file.php',
        'line' => '123'
    ]);
});

// Debug route untuk melihat konfigurasi
Route::get('/debug-config', function () {
    return response()->json([
        'google_client_id' => config('services.google.client_id'),
        'google_client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT SET',
        'google_redirect' => config('services.google.redirect'),
        'session_driver' => config('session.driver'),
        'app_url' => config('app.url'),
        'request_url' => request()->url(),
        'full_url' => request()->fullUrl()
    ]);
});