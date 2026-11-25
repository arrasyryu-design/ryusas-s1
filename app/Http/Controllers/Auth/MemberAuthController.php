<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Pendaftaran & login anggota (siswa/guru) yang sederhana dan OPSIONAL.
 * Menggunakan guard "member" sehingga tidak pernah memberi akses ke panel Admin.
 */
class MemberAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('home');
        }

        return view('auth.member-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau NIS/NIP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'identity_number';

        if (! Auth::guard('member')->attempt([$field => $data['login'], 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withInput($request->only('login'))
                ->withErrors(['login' => 'Email atau kata sandi salah.']);
        }

        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Selamat datang, ' . Auth::guard('member')->user()->name . '.');
    }

    public function showRegister()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('home');
        }

        return view('auth.member-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'class_or_position' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:members,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'email' => 'Format email tidak valid.',
            'min' => ':attribute minimal :min karakter.',
            'confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'max' => ':attribute terlalu panjang (maksimal :max karakter).',
        ], [
            'name' => 'Nama lengkap',
            'class_or_position' => 'Kelas / Jabatan',
            'email' => 'Email',
            'password' => 'Kata sandi',
        ]);

        Member::create($data);

        return redirect()->route('member.login')
            ->with('status', 'Pendaftaran anggota berhasil! Silakan masuk menggunakan akun Anda.');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
