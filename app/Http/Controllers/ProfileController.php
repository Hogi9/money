<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load('roles', 'teams');
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)],
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            ...($request->filled('password') ? ['password' => Hash::make($request->password)] : []),
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
