<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Test route untuk debug Google OAuth
Route::get('/test-google-callback', function () {
    Log::info('Test callback accessed');
    return response()->json([
        'message' => 'Test callback working',
        'auth_check' => auth()->check(),
        'user' => auth()->user()
    ]);
});

// Debug Google OAuth configuration
Route::get('/debug-google', function () {
    return response()->json([
        'google_client_id' => config('services.google.client_id'),
        'google_redirect' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'routes' => [
            'google_redirect' => route('google.redirect'),
            'google_callback' => route('google.callback')
        ]
    ]);
});

// Test login bypass
Route::get('/test-login', function () {
    $user = \App\Models\User::where('email', 'm.alfin.z117@gmail.com')->first();
    
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'ALFIN .z',
            'email' => 'm.alfin.z117@gmail.com',
            'google_id' => '123456789',
            'email_verified_at' => now(),
        ]);
    }
    
    \Illuminate\Support\Facades\Auth::login($user, true);
    
    return redirect('/')->with('success', 'Login berhasil!');
});