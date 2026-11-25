<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPEKA') — SIPEKA {{ config('sipeka.school_name') }}</title>
    @include('partials.head-assets')
</head>

<body class="d-flex flex-column min-vh-100">
    @php $member = auth('member')->user(); @endphp

    <header class="sticky-top" style="z-index:1030;">
        <nav class="navbar navbar-expand-md sp-navbar" aria-label="Navigasi utama">
            <div class="sp-wrap w-100 d-flex flex-wrap align-items-center justify-content-between">
                <a class="sp-brand" href="{{ route('home') }}">
                    <span class="logo"><i class="bi bi-building-check" aria-hidden="true"></i></span>
                    <span><span class="t1">SIPEKA</span><span class="t2">{{ config('sipeka.school_name') }}</span></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Buka menu navigasi">
                    <i class="bi bi-list fs-4" aria-hidden="true"></i>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="publicNav">
                    <div class="d-flex flex-column flex-md-row gap-1 ms-md-3 mt-3 mt-md-0">
                        <a class="sp-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                        <a class="sp-nav-link {{ request()->routeIs('reports.create', 'reports.success') ? 'active' : '' }}" href="{{ route('reports.create') }}">Laporkan Kerusakan</a>
                        <a class="sp-nav-link {{ request()->routeIs('tracking') ? 'active' : '' }}" href="{{ route('tracking') }}">Lacak Laporan</a>
                        <a class="sp-nav-link {{ request()->routeIs('reports.index', 'reports.show') ? 'active' : '' }}" href="{{ route('reports.index') }}">Laporan Publik</a>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mt-3 mt-md-0">
                        @if ($member)
                        <span class="t-label-sm text-white d-none d-lg-inline"><i class="bi bi-person-circle"></i> {{ \Illuminate\Support\Str::limit($member->name, 22) }}</span>
                        <form method="POST" action="{{ route('member.logout') }}">
                            @csrf
                            <button type="submit" class="sp-nav-link"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                        </form>
                        @else
                        <a class="sp-nav-link {{ request()->routeIs('member.*') ? 'active' : '' }}" href="{{ route('member.login') }}"><i class="bi bi-person"></i> Masuk</a>
                        @endif
                        @auth
                        <a class="sp-nav-link sp-nav-outline" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock" aria-hidden="true"></i> Panel Admin</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="sp-footer">
        <div class="sp-wrap py-5">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="t-title-md mb-2">SIPEKA</div>
                    <p class="small-muted mb-0">Sistem Informasi Pelaporan Kerusakan Fasilitas Sekolah — {{ config('sipeka.school_name') }}.</p>
                </div>
                <div class="col-md-4">
                    <div class="t-label-lg mb-2">Informasi Sekolah</div>
                    <p class="small-muted mb-1">{{ config('sipeka.school_address') }}</p>
                    <p class="small-muted mb-0">{{ config('sipeka.school_email') }} · {{ config('sipeka.school_phone') }}</p>
                </div>
                <div class="col-md-3">
                    <div class="t-label-lg mb-2">Tautan Cepat</div>
                    <ul class="list-unstyled mb-0 small-muted">
                        <li><a href="{{ route('reports.create') }}">Laporkan Kerusakan</a></li>
                        <li><a href="{{ route('tracking') }}">Lacak Laporan</a></li>
                        <li><a href="{{ route('reports.index') }}">Laporan Publik</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    @include('partials.foot-assets')
</body>

</html>