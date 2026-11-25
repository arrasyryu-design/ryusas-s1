@extends('layouts.admin')
@section('title', 'Laporan PDF')
@section('page_title', 'Laporan PDF')

@section('content')
<div class="sp-card sp-card-pad" style="max-width: 760px;">
    <h2 class="sp-panel-title mb-1">Ekspor Data Laporan</h2>
    <p class="sp-muted t-body-md mb-4">Pilih filter, lalu tampilkan laporan. Pada halaman yang terbuka, tekan tombol <strong>Cetak / Simpan PDF</strong> dan pilih "Simpan sebagai PDF" sebagai printer.</p>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="GET" action="{{ route('admin.export.print') }}" target="_blank">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach (\App\Enums\ReportStatus::cases() as $s)<option value="{{ $s->value }}">{{ $s->label() }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="category">Kategori</label>
                <select id="category" name="category" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label" for="from">Dari tanggal</label><input type="date" id="from" name="from" class="form-control"></div>
            <div class="col-md-6"><label class="form-label" for="to">Sampai tanggal</label><input type="date" id="to" name="to" class="form-control"></div>
        </div>
        <button type="submit" class="btn btn-sp d-inline-flex align-items-center gap-2"><i class="bi bi-file-earmark-pdf"></i> Tampilkan Laporan</button>
    </form>
</div>
@endsection
