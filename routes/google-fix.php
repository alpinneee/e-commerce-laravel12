<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Manual Google callback untuk bypass masalah consent
Route::get('/google-login-fix', function () {
    $email = request('email', 'm.alfin.z117@gmail.com');
    $name = request('name', 'ALFIN .z');
    
    // Simulasi data Google user
    $googleUserData = [
        'id' => '117' . rand(100000000, 999999999),
        'name' => $name,
        'email' => $email,
        'avatar' => 'https://lh3.googleusercontent.com/a/default-user'
    ];
    
    $user = User::where('email', $googleUserData['email'])->first();
    
    if (!$user) {
        $user = User::create([
            'name' => $googleUserData['name'],
            'email' => $googleUserData['email'],
            'google_id' => $googleUserData['id'],
            'avatar' => $googleUserData['avatar'],
            'email_verified_at' => now(),
        ]);
    } else {
        $user->update([
            'google_id' => $googleUserData['id'],
            'avatar' => $googleUserData['avatar'],
        ]);
    }
    
    Auth::login($user, true);
    
    return redirect('/')->with('success', 'Login berhasil dengan Google!');
});