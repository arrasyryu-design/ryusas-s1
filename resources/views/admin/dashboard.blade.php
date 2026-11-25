@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Admin')

@section('content')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <h2 class="t-headline-md mb-1">Selamat Datang, {{ auth('web')->user()->name }}</h2>
        <p class="sp-muted mb-0">Kelola dan pantau laporan kerusakan fasilitas sekolah.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sp">Kelola Laporan</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sp-outline">Kategori Kerusakan</a>
    </div>
</div>

@php
    $cards = [
        ['Total Laporan', $stats['total'], null],
        ['Menunggu', $stats['menunggu'], 'menunggu'],
        ['Diverifikasi', $stats['diverifikasi'], 'diverifikasi'],
        ['Dalam Pengerjaan', $stats['dalam_pengerjaan'], 'dalam_pengerjaan'],
        ['Menunggu Verifikasi Penyelesaian', $stats['menunggu_verifikasi'], 'menunggu_verifikasi'],
        ['Selesai', $stats['selesai'], 'selesai'],
        ['Ditolak', $stats['ditolak'], 'ditolak'],
    ];
@endphp
<div class="row g-3 mb-4">
    @foreach ($cards as [$label, $num, $status])
        <div class="col-6 col-md-4 col-xl-3 {{ $loop->first ? 'col-xl-3' : '' }}">
            <a href="{{ route('admin.reports.index', $status ? ['status' => $status] : []) }}" class="sp-admin-stat">
                <div class="lbl">{{ $label }}</div>
                <div class="num">{{ number_format($num, 0, ',', '.') }}</div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="sp-card">
            <div class="sp-card-head">
                <div>
                    <h3 class="sp-panel-title">Laporan Perlu Tindakan</h3>
                    <p class="sp-muted t-body-md mb-0">Laporan menunggu verifikasi, prioritas tinggi/darurat, atau menunggu konfirmasi selesai.</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="t-label-lg">Lihat Semua</a>
            </div>
            @if ($needsAttention->isEmpty())
                <div class="sp-empty"><i class="bi bi-check2-circle"></i><p class="mt-2 mb-0">Tidak ada laporan yang perlu tindakan.</p></div>
            @else
                <div class="sp-table-wrap">
                    <table class="sp-table">
                        <thead><tr><th>Kode Laporan</th><th>Judul Laporan</th><th>Lokasi</th><th>Tanggal</th><th>Prioritas</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        @foreach ($needsAttention as $r)
                            <tr>
                                <td><a class="sp-code-link" href="{{ route('admin.reports.show', $r) }}">{{ $r->report_code }}</a></td>
                                <td>{{ \Illuminate\Support\Str::limit($r->title, 38) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($r->location, 28) }}</td>
                                <td class="text-nowrap">{{ $r->created_at->translatedFormat('d M Y') }}</td>
                                <td><x-priority-badge :priority="$r->priority" /></td>
                                <td><x-status-badge :status="$r->status" /></td>
                                <td><a href="{{ route('admin.reports.show', $r) }}" class="btn btn-sp-outline btn-sm">Detail</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="sp-card sp-card-pad">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h3 class="sp-panel-title">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.activity.index') }}" class="t-label-lg">Semua</a>
            </div>
            @if ($activities->isEmpty())
                <p class="sp-muted mb-0">Belum ada aktivitas.</p>
            @else
                <ul class="sp-feed">
                    @foreach ($activities as $a)
                        <li>
                            <div>{{ $a->description }}</div>
                            <div class="when">{{ $a->user?->name ?? 'Sistem' }} · {{ $a->created_at->diffForHumans() }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
