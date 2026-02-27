<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle File Uploads for Logos
        $logos = ['logo_navigation', 'logo_document', 'logo_invoice', 'logo_website'];
        foreach ($logos as $logoKey) {
            if ($request->hasFile($logoKey)) {
                $file = $request->file($logoKey);
                $filename = time() . '_' . $logoKey . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/logos'), $filename);
                $data[$logoKey] = 'uploads/logos/' . $filename;
            }
        }

        // Save each key-value pair to database
        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        // Clear the cache so helpers get fresh data
        Cache::forget('global_settings');

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
