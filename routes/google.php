<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');