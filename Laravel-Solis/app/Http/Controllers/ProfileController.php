<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'address'          => 'nullable|string|max:500',
            'gender'           => 'nullable|in:Male,Female,Other',
            'profile_picture'  => 'nullable|image|max:3048',
        ]);

        $data = $request->only('name', 'email', 'address', 'gender');

        if ($request->hasFile('profile_picture')) {
            $file            = $request->file('profile_picture');
            $mime            = $file->getMimeType();
            $content         = base64_encode(file_get_contents($file->getRealPath()));
            $data['profile_picture'] = 'data:' . $mime . ';base64,' . $content;
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
