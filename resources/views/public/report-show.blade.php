@extends('layouts.public')
@section('title', $report->title)

@section('content')
<div class="sp-wrap sp-section" style="max-width: 900px;">
    <a href="{{ route('reports.index') }}" class="d-inline-flex align-items-center gap-1 t-label-lg text-decoration-none mb-3"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Laporan Publik</a>

    <div class="sp-card sp-card-pad mb-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
            <div>
                <div class="sp-code-link fs-6 mb-1">{{ $report->report_code }}</div>
                <h1 class="t-headline-md mb-0">{{ $report->title }}</h1>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <x-status-badge :status="$report->status" />
                <x-priority-badge :priority="$report->priority" />
            </div>
        </div>
        <div class="row g-3 t-body-md mb-4">
            <div class="col-sm-4"><div class="t-label-sm sp-muted">Kategori</div>{{ $report->category->name }}</div>
            <div class="col-sm-4"><div class="t-label-sm sp-muted">Lokasi</div>{{ $report->location }}</div>
            <div class="col-sm-4"><div class="t-label-sm sp-muted">Tanggal laporan</div>{{ $report->created_at->translatedFormat('d M Y') }}</div>
        </div>
        <h2 class="t-title-md mb-2">Deskripsi Kerusakan</h2>
        <p class="mb-0" style="white-space: pre-line;">{{ $report->description }}</p>
    </div>

    <div class="sp-card sp-card-pad mb-4">
        <h2 class="t-title-lg mb-3">Lini Masa Penanganan Publik</h2>
        <x-timeline :updates="$updates" />
    </div>

    <div class="sp-card sp-card-pad">
        <h2 class="t-title-lg mb-3">Foto Dokumentasi</h2>
        <x-gallery :attachments="$report->attachments" />
    </div>
</div>
@endsection
