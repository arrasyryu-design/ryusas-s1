@extends('layouts.auth')
@section('title', 'Masuk Anggota')

@section('content')
<h1 class="t-headline-md mb-1">Masuk Anggota SIPEKA</h1>
<p class="sp-muted t-body-md mb-4">Akun anggota bersifat opsional. Anda tetap dapat melapor dan melacak laporan tanpa akun.</p>

<x-flash-message />

<form method="POST" action="{{ route('member.login.submit') }}" data-once novalidate>
    @csrf
    <div class="mb-3">
        <label class="form-label" for="login">Email <span class="text-danger">*</span></label>
        <div class="input-icon"><i class="bi bi-person" aria-hidden="true"></i>
            <input type="text" id="login" name="login" class="form-control @error('login') is-invalid @enderror" placeholder="raka.pratama@sman1nusantara.sch.id" value="{{ old('login') }}" autocomplete="username" required autofocus>
        </div>
        @error('login')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Kata Sandi <span class="text-danger">*</span></label>
        <div class="input-icon"><i class="bi bi-lock" aria-hidden="true"></i>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-sp w-100 py-2">Masuk Anggota</button>
</form>

<hr class="my-4" style="border-color: var(--sp-outline); opacity: 1;">
<p class="t-body-md text-center mb-2">Belum punya akun anggota? <a href="{{ route('member.register') }}" class="fw-semibold">Daftar sekarang</a></p>
<p class="t-body-md text-center sp-muted mb-0">Atau <a href="{{ route('reports.create') }}" class="fw-semibold">lapor cepat tanpa akun</a></p>
@endsection
