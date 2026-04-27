<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginValue = $request->input('username');
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->put('show_tour', true);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Email/username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerProcess(Request $request)
    {
        $request->validate([
            'cf-turnstile-response' => ['required'],
        ]);

        $turnstile = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret_key'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $turnstile->json('success')) {
            return back()->withErrors(['cf-turnstile-response' => 'Verifikasi gagal. Silakan coba lagi.'])->onlyInput('name', 'username', 'email');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('user');

        // Auth::login($user);

        return redirect('/login')->with('success', 'Registration successful. Please log in.');
    }

    public function resetPassword()
    {
        return view('auth.forget-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Here you would typically send the reset link email using Laravel's built-in password reset functionality.
        // For demonstration purposes, we'll just pretend we sent the email.
        return redirect()
            ->route('login')
            ->with('success', 'Reset link has been sent to your email.');
    }
}
