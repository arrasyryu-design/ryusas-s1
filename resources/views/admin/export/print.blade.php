<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Laporan Kerusakan Fasilitas — SIPEKA</title>
    @include('partials.head-assets')
</head>
<body>
<div class="print-sheet">
    <div class="no-print d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <a href="{{ route('admin.export.index') }}" class="btn btn-sp-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button type="button" class="btn btn-sp btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
    </div>

    <h1 style="font-size:18px;margin:0;">Laporan Kerusakan Fasilitas Sekolah</h1>
    <div style="font-size:13px;margin-bottom:12px;">
        {{ config('sipeka.school_name') }}<br>
        Status: {{ $filters['status'] }} · Kategori: {{ $filters['category'] }}
        @if ($filters['from'] || $filters['to']) · Periode: {{ $filters['from'] ?: '…' }} s.d. {{ $filters['to'] ?: '…' }} @endif
        <br>Dicetak: {{ now()->translatedFormat('d M Y H:i') }} WIB · Jumlah laporan: {{ $reports->count() }}
    </div>

    <table>
        <thead><tr><th style="width:36px;">No</th><th>Kode</th><th>Judul</th><th>Kategori</th><th>Lokasi</th><th>Tanggal</th><th>Prioritas</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($reports as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->report_code }}</td>
                <td>{{ $r->title }}</td>
                <td>{{ $r->category->name }}</td>
                <td>{{ $r->location }}</td>
                <td>{{ $r->created_at->translatedFormat('d M Y') }}</td>
                <td>{{ $r->priority->label() }}</td>
                <td>{{ $r->status->label() }}</td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center;">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('partials.foot-assets')
</body>
</html>
