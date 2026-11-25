@extends('layouts.public')
@section('title', 'Laporan Berhasil Dikirim')

@section('content')
<div class="sp-wrap sp-section" style="max-width: 680px;">
    <div class="sp-card sp-card-pad text-center">
        <span class="sp-icon-box mx-auto mb-3" style="width:56px;height:56px;font-size:28px;background:var(--sp-ok-text);"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
        <h1 class="t-headline-md mb-2">Laporan Berhasil Dikirim</h1>
        <p class="sp-muted mb-4">Simpan kode pelacakan unik di bawah ini untuk memantau status verifikasi dan progres perbaikan fasilitas oleh admin sekolah.</p>
        <div class="sp-id-box mb-4">
            <div class="t-label-sm sp-muted mb-1">Kode Pelacakan Anda</div>
            <div class="sp-code-big">{{ $code }}</div>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <button type="button" class="btn btn-sp-outline d-inline-flex align-items-center gap-2" data-copy="{{ $code }}"><i class="bi bi-clipboard" aria-hidden="true"></i> Salin Kode</button>
            <a href="{{ route('tracking', ['kode' => $code]) }}" class="btn btn-sp d-inline-flex align-items-center gap-2"><i class="bi bi-search" aria-hidden="true"></i> Lacak Status Laporan</a>
            <a href="{{ route('home') }}" class="btn btn-sp-outline">Kembali ke Beranda</a>
        </div>
        <p class="t-label-sm sp-muted mt-4 mb-0">Kode ini tidak dapat ditampilkan ulang. Catat atau salin sekarang.</p>
    </div>
</div>
@endsection
