<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // $taxRate = Setting::firstOrCreate(['key' => 'tax_rate'], ['value' => '10']);
        return view('settings.index'); // No longer passing taxRate
    }

    public function update(Request $request)
    {
        return back()->with('success', 'Pengaturan metode lama tidak lagi digunakan.');
    }
}
