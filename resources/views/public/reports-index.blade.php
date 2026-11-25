@extends('layouts.public')
@section('title', 'Laporan Publik')

@section('content')
<div class="sp-wrap sp-section">
    <div class="mb-4" style="max-width: 70ch;">
        <h1 class="t-headline-lg mb-2">Daftar Laporan Publik</h1>
        <p class="t-body-lg sp-muted mb-0">Menampilkan laporan kerusakan fasilitas sekolah yang telah diverifikasi oleh admin. Identitas pelapor tidak ditampilkan untuk menjaga privasi.</p>
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="sp-card sp-card-pad mb-4">
        <div class="row g-2">
            <div class="col-lg-5">
                <div class="input-icon"><i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" class="form-control" placeholder="Cari judul, lokasi, atau kode laporan..." value="{{ request('q') }}" aria-label="Cari laporan">
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <select name="category" class="form-select" aria-label="Filter kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <select name="status" class="form-select" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    @foreach (['diverifikasi' => 'Diverifikasi', 'dalam_pengerjaan' => 'Dalam Pengerjaan', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'selesai' => 'Selesai'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-sp flex-grow-1" type="submit">Terapkan</button>
                @if (request()->hasAny(['q', 'category', 'status']))<a href="{{ route('reports.index') }}" class="btn btn-sp-outline" title="Reset filter" aria-label="Reset filter"><i class="bi bi-x-lg"></i></a>@endif
            </div>
        </div>
    </form>

    @if ($reports->isEmpty())
        <div class="sp-card sp-empty"><i class="bi bi-inbox" aria-hidden="true"></i><p class="mt-2 mb-0">Tidak ada laporan yang sesuai.</p></div>
    @else
        <div class="row g-4">
            @foreach ($reports as $report)
                <div class="col-md-6 col-lg-4"><x-report-card :report="$report" /></div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">{{ $reports->links() }}</div>
    @endif
</div>
@endsection
