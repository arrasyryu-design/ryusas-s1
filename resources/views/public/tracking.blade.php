@extends('layouts.public')
@section('title', 'Lacak Laporan')

@section('content')
<div class="sp-wrap sp-section" style="max-width: 860px;">
    <div class="mb-4">
        <h1 class="t-headline-lg mb-2">Lacak Status Laporan</h1>
        <p class="t-body-lg sp-muted mb-0">Masukkan kode pelacakan unik yang Anda terima saat mengirim laporan untuk melihat status dan progres perbaikan terkini.</p>
    </div>

    <form method="GET" action="{{ route('tracking') }}" class="sp-card sp-card-pad mb-4">
        <label class="form-label" for="kode">Kode Pelacakan</label>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <div class="input-icon flex-grow-1">
                <i class="bi bi-upc-scan" aria-hidden="true"></i>
                <input type="text" id="kode" name="kode" class="form-control sp-mono" maxlength="40" placeholder="Masukkan kode, mis. SKL-20261124-A8F3D2" value="{{ $code }}" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-sp d-inline-flex align-items-center justify-content-center gap-2"><i class="bi bi-search" aria-hidden="true"></i> Cari Laporan</button>
        </div>
    </form>

    @if ($searched && ! $report)
        <div class="sp-card sp-empty">
            <i class="bi bi-search" aria-hidden="true"></i>
            <h2 class="t-title-md mt-2">Laporan tidak ditemukan</h2>
            <p class="mb-0">Periksa kembali penulisan kode pelacakan Anda, lalu coba lagi.</p>
        </div>
    @elseif ($report)
        <div class="sp-card sp-card-pad mb-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                <div>
                    <div class="sp-code-link fs-6 mb-1">{{ $report->report_code }}</div>
                    <h2 class="t-headline-md mb-0">{{ $report->title }}</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <x-status-badge :status="$report->status" />
                    <x-priority-badge :priority="$report->priority" />
                </div>
            </div>
            <div class="row g-3 t-body-md">
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Kategori</div>{{ $report->category->name }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Lokasi</div>{{ $report->location }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Tanggal laporan</div>{{ $report->created_at->translatedFormat('d M Y') }}</div>
            </div>
        </div>

        <div class="sp-card sp-card-pad mb-4">
            <h2 class="t-title-lg mb-3">Lini Masa Progres Perbaikan</h2>
            <x-timeline :updates="$updates" />
        </div>

        <div class="sp-card sp-card-pad">
            <h2 class="t-title-lg mb-3">Dokumentasi Foto</h2>
            <x-gallery :attachments="$report->attachments" />
        </div>
    @endif
</div>
@endsection
