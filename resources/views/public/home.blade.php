@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<section>
    <div class="sp-wrap sp-section">
        <x-flash-message />
        <div class="row align-items-center g-5">
            <div class="col-lg-7 d-flex flex-column gap-4">
                <span class="sp-pill align-self-start"><i class="bi bi-megaphone" aria-hidden="true"></i> Layanan Pengaduan Sarana &amp; Prasarana</span>
                <h1 class="t-display mb-0">Laporkan Kerusakan Fasilitas Sekolah dengan Cepat dan Transparan</h1>
                <p class="t-body-lg sp-muted mb-0" style="max-width: 62ch;">
                    SIPEKA memudahkan siswa dan warga sekolah melaporkan kerusakan ruang kelas, toilet, laboratorium, maupun fasilitas umum tanpa perlu membuat akun. Pantau setiap proses perbaikan melalui kode pelacakan unik.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('reports.create') }}" class="btn btn-sp d-inline-flex align-items-center gap-2 px-4 py-2"><i class="bi bi-pencil-square" aria-hidden="true"></i> Laporkan Kerusakan</a>
                    <a href="{{ route('tracking') }}" class="btn btn-sp-outline d-inline-flex align-items-center gap-2 px-4 py-2"><i class="bi bi-search" aria-hidden="true"></i> Lacak Laporan</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="sp-hero-img"><img src="{{ asset('images/hero-school.jpg') }}" alt="Ilustrasi gedung sekolah {{ config('sipeka.school_name') }}"></div>
            </div>
        </div>
    </div>
</section>

{{-- Statistik --}}
<section class="pb-5">
    <div class="sp-wrap">
        <div class="row g-3">
            <div class="col-12 col-md-4"><div class="sp-stat"><div class="t-label-sm sp-muted">Total laporan masuk</div><div class="num">{{ number_format($stats['total'], 0, ',', '.') }}</div></div></div>
            <div class="col-6 col-md-4"><div class="sp-stat"><div class="t-label-sm sp-muted">Sedang ditangani</div><div class="num">{{ number_format($stats['process'], 0, ',', '.') }}</div></div></div>
            <div class="col-6 col-md-4"><div class="sp-stat"><div class="t-label-sm sp-muted">Laporan selesai</div><div class="num">{{ number_format($stats['done'], 0, ',', '.') }}</div></div></div>
        </div>
    </div>
</section>

{{-- Panduan pelaporan --}}
<section class="sp-band sp-section" id="panduan">
    <div class="sp-wrap d-flex flex-column gap-4">
        <div style="max-width: 62ch;">
            <h2 class="t-headline-lg mb-2">Cara Kerja SIPEKA</h2>
            <p class="t-body-lg sp-muted mb-0">Tiga langkah sederhana untuk melaporkan kerusakan fasilitas sekolah hingga selesai diperbaiki.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="sp-step d-flex flex-column gap-2">
                <span class="sp-icon-box"><i class="bi bi-camera" aria-hidden="true"></i></span>
                <h3 class="t-title-lg mb-0">1. Isi Formulir &amp; Unggah Foto</h3>
                <p class="t-body-md sp-muted mb-0">Pilih kategori kerusakan, tuliskan lokasi fasilitas secara jelas, lalu lampirkan foto kondisi kerusakan tanpa perlu login.</p>
            </div></div>
            <div class="col-md-4"><div class="sp-step d-flex flex-column gap-2">
                <span class="sp-icon-box"><i class="bi bi-upc-scan" aria-hidden="true"></i></span>
                <h3 class="t-title-lg mb-0">2. Simpan Kode Pelacakan</h3>
                <p class="t-body-md sp-muted mb-0">Setelah laporan terkirim, sistem langsung menerbitkan kode pelacakan unik untuk memantau status verifikasi admin.</p>
            </div></div>
            <div class="col-md-4"><div class="sp-step d-flex flex-column gap-2">
                <span class="sp-icon-box"><i class="bi bi-activity" aria-hidden="true"></i></span>
                <h3 class="t-title-lg mb-0">3. Pantau Progres Perbaikan</h3>
                <p class="t-body-md sp-muted mb-0">Masukkan kode pelacakan kapan saja untuk melihat status, catatan perkembangan, dan foto hasil perbaikan dari admin.</p>
            </div></div>
        </div>
    </div>
</section>

{{-- Laporan terbaru --}}
<section class="sp-section">
    <div class="sp-wrap d-flex flex-column gap-4">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div style="max-width: 62ch;">
                <h2 class="t-headline-lg mb-2">Laporan Terbaru</h2>
                <p class="t-body-lg sp-muted mb-0">Laporan yang sudah diverifikasi admin. Identitas pelapor tidak ditampilkan.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-sp-outline d-inline-flex align-items-center gap-2">Lihat Semua Laporan <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
        @if ($latest->isEmpty())
            <div class="sp-card sp-empty"><i class="bi bi-inbox" aria-hidden="true"></i><p class="mt-2 mb-0">Belum ada laporan terverifikasi.</p></div>
        @else
            <div class="row g-4">
                @foreach ($latest as $report)
                    <div class="col-md-6 col-lg-4"><x-report-card :report="$report" /></div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
