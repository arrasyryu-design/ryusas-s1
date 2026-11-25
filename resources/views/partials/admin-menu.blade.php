@php
    $st = request('status');
    $onList = request()->routeIs('admin.reports.index');
    $items = [
        ['Dashboard', 'bi-speedometer2', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
        ['Semua Laporan', 'bi-folder2-open', route('admin.reports.index'), request()->routeIs('admin.reports.*') && ! ($onList && in_array($st, ['menunggu', 'dalam_pengerjaan', 'menunggu_verifikasi', 'selesai', 'ditolak'], true))],
        ['Menunggu Verifikasi', 'bi-hourglass-split', route('admin.reports.index', ['status' => 'menunggu']), $onList && $st === 'menunggu'],
        ['Dalam Pengerjaan', 'bi-tools', route('admin.reports.index', ['status' => 'dalam_pengerjaan']), $onList && $st === 'dalam_pengerjaan'],
        ['Menunggu Penyelesaian', 'bi-clipboard-check', route('admin.reports.index', ['status' => 'menunggu_verifikasi']), $onList && $st === 'menunggu_verifikasi'],
        ['Laporan Selesai', 'bi-check2-circle', route('admin.reports.index', ['status' => 'selesai']), $onList && $st === 'selesai'],
        ['Laporan Ditolak', 'bi-x-circle', route('admin.reports.index', ['status' => 'ditolak']), $onList && $st === 'ditolak'],
    ];
    $items2 = [
        ['Kategori Kerusakan', 'bi-tags', route('admin.categories.index'), request()->routeIs('admin.categories.*')],
        ['Riwayat Aktivitas', 'bi-clock-history', route('admin.activity.index'), request()->routeIs('admin.activity.*')],
        ['Laporan PDF', 'bi-file-earmark-pdf', route('admin.export.index'), request()->routeIs('admin.export.*')],
    ];
@endphp
<div class="sp-side-head">
    <div class="d-flex align-items-center gap-2">
        <span class="sp-brand"><span class="logo"><i class="bi bi-building-check" aria-hidden="true"></i></span></span>
        <div>
            <div class="t-title-md text-white lh-1">SIPEKA</div>
            <div class="t-label-sm" style="color: var(--sp-primary-subtle);">Panel Admin</div>
        </div>
    </div>
</div>
<nav class="sp-side-nav" aria-label="Menu admin">
    @foreach ($items as [$label, $icon, $url, $active])
        <a href="{{ $url }}" class="sp-side-link {{ $active ? 'active' : '' }}" @if($active) aria-current="page" @endif><i class="bi {{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
    @endforeach
    <div class="sp-side-sep">Pengelolaan</div>
    @foreach ($items2 as [$label, $icon, $url, $active])
        <a href="{{ $url }}" class="sp-side-link {{ $active ? 'active' : '' }}" @if($active) aria-current="page" @endif><i class="bi {{ $icon }}" aria-hidden="true"></i> {{ $label }}</a>
    @endforeach
</nav>
<div class="sp-side-foot">
    <a href="{{ route('home') }}" class="sp-side-link" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Lihat Situs Publik</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sp-side-link"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Logout</button>
    </form>
</div>
