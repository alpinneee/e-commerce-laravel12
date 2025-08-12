<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Store settings in cache or database
        cache()->put('settings.site_name', $request->site_name);
        cache()->put('settings.site_description', $request->site_description);
        cache()->put('settings.contact_email', $request->contact_email);
        cache()->put('settings.contact_phone', $request->contact_phone);
        cache()->put('settings.address', $request->address);

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan');
    }
}