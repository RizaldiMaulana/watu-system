<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($request->user()->signature) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->signature);
            }
            $path = $request->file('signature')->store('signatures', 'public');
            $request->user()->signature = $path;
        } elseif ($request->filled('signature_data')) {
            // Handle Base64 Upload
            $data = $request->signature_data;
            // Remove header "data:image/png;base64,"
            $image_parts = explode(";base64,", $data);
            if (count($image_parts) >= 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'png';
                $image_base64 = base64_decode($image_parts[1]);
                
                // Filename
                $filename = 'signatures/' . uniqid() . '.' . $image_type;
                
                // Delete old
                 if ($request->user()->signature) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->signature);
                }
                
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
                $request->user()->signature = $filename;
            }
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
