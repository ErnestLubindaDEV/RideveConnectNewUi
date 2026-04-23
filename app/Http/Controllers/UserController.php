<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    /**
     * Show the profile settings form.
     */
    public function edit()
    {
        // dd(Auth::user()->profile_picture);

        return view('User.profile_settings', ['user' => Auth::user()]);
    }

    
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validate the request
    $validatedData = $request->validate([
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50048',
        'current_password' => 'nullable|string|min:6',
        'new_password' => 'nullable|string|min:6|confirmed',
    ]);

    // Handle profile picture update
    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture) {
            Storage::delete('public/profile_picture/' . $user->profile_picture);
        }
        $fileName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->storeAs('public/profile_picture', $fileName);
        $user->profile_picture = $fileName;
    }

    // Handle password update
    if ($request->filled('current_password') && $request->filled('new_password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);
        }
        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        $user = Auth::user();
    
        // ❌ Possible issue: Checking after password is already updated
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
    
        // ✅ Only update if current password is correct
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return redirect()->route('profile.settings')->with('success', 'Password updated successfully.');
    }
    
}
