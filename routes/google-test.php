<?php

use Illuminate\Support\Facades\Route;

// Form untuk test login dengan email berbeda
Route::get('/google-test-form', function () {
    return '
    <form action="/google-login-fix" method="GET" style="padding: 20px; font-family: Arial;">
        <h3>Test Google Login</h3>
        <div style="margin: 10px 0;">
            <label>Email:</label><br>
            <input type="email" name="email" value="m.alfin.z117@gmail.com" style="width: 300px; padding: 5px;">
        </div>
        <div style="margin: 10px 0;">
            <label>Name:</label><br>
            <input type="text" name="name" value="ALFIN .z" style="width: 300px; padding: 5px;">
        </div>
        <button type="submit" style="padding: 10px 20px; background: #4285f4; color: white; border: none; border-radius: 5px;">Login dengan Google</button>
    </form>';
});