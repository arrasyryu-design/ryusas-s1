@extends('layouts.admin')
@section('title', 'Kelola Laporan')
@section('page_title', 'Kelola Laporan Kerusakan')

@section('content')
<form method="GET" action="{{ route('admin.reports.index') }}" class="sp-card sp-card-pad mb-4">
    <div class="row g-2">
        <div class="col-lg-4">
            <div class="input-icon"><i class="bi bi-search" aria-hidden="true"></i>
                <input type="search" name="q" class="form-control" placeholder="Cari kode, judul, atau lokasi..." value="{{ request('q') }}" aria-label="Cari laporan">
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <select name="status" class="form-select" aria-label="Filter status">
                <option value="">Semua Status</option>
                @foreach (\App\Enums\ReportStatus::cases() as $s)<option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>@endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-2">
            <select name="category" class="form-select" aria-label="Filter kategori">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-2">
            <select name="priority" class="form-select" aria-label="Filter prioritas">
                <option value="">Semua Prioritas</option>
                @foreach (\App\Enums\ReportPriority::cases() as $p)<option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>@endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-2 d-flex gap-2">
            <button class="btn btn-sp flex-grow-1" type="submit">Terapkan</button>
            @if (request()->hasAny(['q', 'status', 'category', 'priority']))<a href="{{ route('admin.reports.index') }}" class="btn btn-sp-outline" aria-label="Reset filter"><i class="bi bi-x-lg"></i></a>@endif
        </div>
    </div>
</form>

<div class="sp-card">
    @if ($reports->isEmpty())
        <div class="sp-empty"><i class="bi bi-inbox"></i><p class="mt-2 mb-0">Tidak ada laporan yang sesuai.</p></div>
    @else
        <div class="sp-table-wrap">
            <table class="sp-table">
                <thead><tr><th>Kode</th><th>Judul Laporan</th><th>Kategori</th><th>Lokasi</th><th>Tanggal</th><th>Prioritas</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach ($reports as $r)
                    <tr>
                        <td><a class="sp-code-link" href="{{ route('admin.reports.show', $r) }}">{{ $r->report_code }}</a></td>
                        <td>{{ \Illuminate\Support\Str::limit($r->title, 40) }}</td>
                        <td>{{ $r->category->name }}</td>
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
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-top" style="border-color: var(--sp-outline) !important;">
            <span class="t-label-sm sp-muted">Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan</span>
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
