<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index');
    }

    public function markOnboardingDone(Request $request)
    {
        $request->user()->update(['has_seen_onboarding' => true]);
        return response()->json(['status' => 'ok']);
    }
}
