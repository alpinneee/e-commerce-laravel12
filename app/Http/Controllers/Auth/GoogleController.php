<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        $socialite = Socialite::driver('google');
        
        // Disable SSL verification for development
        if (config('app.env') === 'local') {
            $socialite->setHttpClient(new \GuzzleHttp\Client([
                'verify' => false
            ]));
        }
        
        return $socialite->redirect();
    }

    public function callback()
    {
        try {
            Log::info('Google callback started');
            
            // Disable SSL verification for development
            $socialite = Socialite::driver('google');
            if (config('app.env') === 'local') {
                $socialite->setHttpClient(new \GuzzleHttp\Client([
                    'verify' => false
                ]));
            }
            
            $googleUser = $socialite->user();
            
            Log::info('Google user data:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);
            
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? null,
                    'email_verified_at' => now(),
                ]);
                Log::info('Created new user:', ['user_id' => $user->id]);
            } else {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
                Log::info('Updated existing user:', ['user_id' => $user->id]);
            }
            
            Auth::login($user, true);
            Log::info('User logged in:', ['user_id' => $user->id, 'auth_check' => Auth::check()]);
            
            return redirect('/')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            Log::error('Google OAuth error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('auth.google-error', [
                'error' => $e->getMessage(),
                'details' => 'Google OAuth callback failed',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}