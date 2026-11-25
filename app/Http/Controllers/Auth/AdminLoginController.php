<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function show()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $ok = Auth::guard('web')->attempt($credentials + ['is_active' => true], $request->boolean('remember'));

        if (! $ok) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah, atau akun tidak aktif.']);
        }

        $request->session()->regenerate();
        ActivityLogger::log('login', 'Admin masuk ke sistem');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            ActivityLogger::log('logout', 'Admin keluar dari sistem');
            Auth::guard('web')->logout();
        }
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari sistem.');
    }
}
