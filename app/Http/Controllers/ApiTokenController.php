<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    public function generate(Request $request)
    {
        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return redirect()->route('profile.show')->with('api_token', $token);
    }

    public function reset(Request $request)
    {
        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return redirect()->route('profile.show')->with('api_token', $token);
    }

    public function revoke(Request $request)
    {
        Auth::user()->tokens()->delete();

        return redirect()->route('profile.show')->with('success', 'API token revoked successfully.');
    }
}
