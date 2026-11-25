@extends('layouts.auth')
@section('title', 'Daftar Anggota')

@section('content')
<h1 class="t-headline-md mb-1">Daftar Anggota SIPEKA</h1>
<p class="sp-muted t-body-md mb-4">Pendaftaran bersifat opsional. Tanpa akun pun Anda tetap dapat mengirim dan melacak laporan kerusakan.</p>

<form method="POST" action="{{ route('member.register.submit') }}" data-once novalidate>
    @csrf
    @php
        $fields = [
            ['name', 'Nama Lengkap', 'text', 'Contoh: Raka Pratama', 'name'],
            ['class_or_position', 'Kelas / Jabatan', 'text', 'Contoh: XI IPA 2 atau Guru', 'off'],
            ['email', 'Email', 'email', 'nama@sman1nusantara.sch.id', 'email'],
            ['password', 'Kata Sandi', 'password', 'Minimal 8 karakter', 'new-password'],
            ['password_confirmation', 'Konfirmasi Sandi', 'password', 'Ulangi kata sandi', 'new-password'],
        ];
    @endphp
    @foreach ($fields as [$name, $label, $type, $ph, $ac])
        <div class="mb-3">
            <label class="form-label" for="{{ $name }}">{{ $label }} <span class="text-danger">*</span></label>
            <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" placeholder="{{ $ph }}" autocomplete="{{ $ac }}" @if($type !== 'password') value="{{ old($name) }}" @endif required>
            @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endforeach
    <button type="submit" class="btn btn-sp w-100 py-2 mt-2">Daftar Sekarang</button>
</form>

<hr class="my-4" style="border-color: var(--sp-outline); opacity: 1;">
<p class="t-body-md text-center mb-2">Sudah punya akun anggota? <a href="{{ route('member.login') }}" class="fw-semibold">Masuk di sini</a></p>
<p class="t-body-md text-center sp-muted mb-0">Ingin melapor tanpa akun? <a href="{{ route('reports.create') }}" class="fw-semibold">Laporkan Kerusakan</a></p>
@endsection
