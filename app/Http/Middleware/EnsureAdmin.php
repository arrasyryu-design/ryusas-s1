<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hanya akun Admin (guard "web", tabel users) yang aktif boleh masuk.
 * Akun anggota (guard "member") tidak pernah lolos di sini.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('web');

        if (! $guard->check()) {
            return redirect()->route('login');
        }

        if (! $guard->user()->is_active) {
            $guard->logout();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'Akun admin ini sudah dinonaktifkan.']);
        }

        return $next($request);
    }
}
