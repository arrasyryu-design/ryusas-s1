@extends('layouts.auth')
@section('title', 'Masuk Panel Admin')

@section('content')
<h1 class="t-headline-md mb-1">Masuk Panel Admin</h1>
<p class="sp-muted t-body-md mb-4">Masuk menggunakan akun admin sarana prasarana untuk memverifikasi dan mengelola laporan kerusakan.</p>

<x-flash-message />

<form method="POST" action="{{ route('login.submit') }}" data-once novalidate>
    @csrf
    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <div class="input-icon"><i class="bi bi-envelope" aria-hidden="true"></i>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="admin@sman1nusantara.sch.id" value="{{ old('email') }}" autocomplete="username" required autofocus>
        </div>
        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Kata Sandi</label>
        <div class="input-icon"><i class="bi bi-lock" aria-hidden="true"></i>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
            <label class="form-check-label t-body-md" for="remember">Ingat saya</label>
        </div>
        <span class="t-label-sm sp-muted">Hubungi TU jika lupa sandi</span>
    </div>
    <button type="submit" class="btn btn-sp w-100 py-2">Masuk ke Dashboard</button>
</form>

<p class="t-body-md sp-muted text-center mt-4 mb-0">Siswa tidak perlu masuk akun untuk melapor. Gunakan halaman <a href="{{ route('reports.create') }}" class="fw-semibold">Laporkan Kerusakan</a>.</p>
@endsection
