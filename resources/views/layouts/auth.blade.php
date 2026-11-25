<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — SIPEKA {{ config('sipeka.school_name') }}</title>
    @include('partials.head-assets')
</head>
<body>
<div class="sp-auth">
    <div class="sp-auth-card">
        <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-1 mb-3 t-label-lg text-decoration-none">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda Publik
        </a>
        <div class="sp-auth-brand">
            <span class="logo"><i class="bi bi-building-check" aria-hidden="true"></i></span>
            <span class="t-title-lg">SIPEKA · {{ config('sipeka.school_name') }}</span>
        </div>
        <div class="sp-card sp-card-pad">
            @yield('content')
        </div>
        <p class="text-center sp-muted t-label-sm mt-4 mb-0">SIPEKA · Sistem Informasi Pelaporan Kerusakan Fasilitas Sekolah</p>
    </div>
</div>
@include('partials.foot-assets')
</body>
</html>
