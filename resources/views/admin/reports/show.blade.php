@extends('layouts.admin')
@section('title', $report->report_code)
@section('page_title', 'Detail Laporan')

@section('content')
@php
    $canUpdate = ! in_array($report->status, [\App\Enums\ReportStatus::Pending, \App\Enums\ReportStatus::Rejected], true);
    $statusOptions = array_merge([$report->status], $report->status->next());
@endphp

<a href="{{ route('admin.reports.index') }}" class="d-inline-flex align-items-center gap-1 t-label-lg text-decoration-none mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan</a>

<div class="row g-4">
    <div class="col-xl-8 d-flex flex-column gap-4">
        <div class="sp-card sp-card-pad">
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
            <div class="row g-3 t-body-md mb-4">
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Kategori</div>{{ $report->category->name }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Lokasi</div>{{ $report->location }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Tanggal laporan</div>{{ $report->created_at->translatedFormat('d M Y · H:i') }} WIB</div>
            </div>
            <h3 class="t-title-md mb-2">Deskripsi Kerusakan</h3>
            <p class="mb-0" style="white-space: pre-line;">{{ $report->description }}</p>
        </div>

        <div class="sp-card sp-card-pad">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="sp-panel-title">Identitas Pelapor</h3>
                <span class="sp-badge pr-rendah">Khusus Admin</span>
            </div>
            <div class="row g-3 t-body-md">
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Nama</div>{{ $report->reporter_name ?: '—' }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Kelas</div>{{ $report->reporter_class ?: '—' }}</div>
                <div class="col-sm-4"><div class="t-label-sm sp-muted">Kontak</div>{{ $report->reporter_contact ?: '—' }}</div>
            </div>
        </div>

        <div class="sp-card sp-card-pad">
            <h3 class="sp-panel-title mb-3">Dokumentasi Foto</h3>
            @forelse (['before', 'progress', 'after'] as $type)
                @if (isset($attachments[$type]))
                    <h4 class="t-label-lg mb-2 {{ $loop->first ? '' : 'mt-4' }}">{{ \App\Models\ReportAttachment::TYPES[$type] }}</h4>
                    <x-gallery :attachments="$attachments[$type]" />
                @endif
            @empty
            @endforelse
            @if ($attachments->isEmpty())<p class="sp-muted mb-0">Belum ada foto.</p>@endif
        </div>

        <div class="sp-card sp-card-pad">
            <h3 class="sp-panel-title mb-3">Riwayat Penanganan Laporan</h3>
            <x-timeline :updates="$report->updates->sortByDesc('id')->values()" :show-admin="true" />
        </div>
    </div>

    <div class="col-xl-4 d-flex flex-column gap-4">
        @if ($report->status === \App\Enums\ReportStatus::Pending)
            <div class="sp-card sp-card-pad">
                <h3 class="sp-panel-title mb-1">Tindakan Verifikasi</h3>
                <p class="sp-muted t-body-md">Periksa foto dan deskripsi, lalu verifikasi atau tolak laporan ini.</p>
                <form method="POST" action="{{ route('admin.reports.verify', $report) }}" data-once class="mb-3">
                    @csrf
                    <label class="form-label" for="verify_priority">Prioritas Laporan</label>
                    <select id="verify_priority" name="priority" class="form-select mb-3 @error('priority') is-invalid @enderror">
                        @foreach (\App\Enums\ReportPriority::cases() as $p)<option value="{{ $p->value }}" @selected(old('priority', $report->priority->value) === $p->value)>{{ $p->label() }}</option>@endforeach
                    </select>
                    @error('priority')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-sp w-100"><i class="bi bi-patch-check"></i> Verifikasi Laporan</button>
                </form>
                <button type="button" class="btn btn-sp-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle"></i> Tolak Laporan</button>
            </div>
        @elseif ($report->status === \App\Enums\ReportStatus::Rejected)
            <div class="sp-card sp-card-pad">
                <h3 class="sp-panel-title mb-2">Laporan Ditolak</h3>
                <p class="sp-muted t-body-md mb-0">Laporan ini telah ditolak dan tidak tampil di daftar publik. Alasan penolakan tercatat pada riwayat penanganan.</p>
            </div>
        @endif

        @if ($canUpdate)
            <div class="sp-card sp-card-pad" data-photo-zone>
                <h3 class="sp-panel-title mb-3">Pembaruan Status &amp; Progres</h3>
                <form method="POST" action="{{ route('admin.reports.status', $report) }}" enctype="multipart/form-data" data-once novalidate>
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label" for="priority">Prioritas Laporan</label>
                        <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror">
                            @foreach (\App\Enums\ReportPriority::cases() as $p)<option value="{{ $p->value }}" @selected(old('priority', $report->priority->value) === $p->value)>{{ $p->label() }}</option>@endforeach
                        </select>
                        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status">Status Penanganan</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach ($statusOptions as $s)<option value="{{ $s->value }}" @selected(old('status', $report->status->value) === $s->value)>{{ $s->label() }}{{ $s === $report->status ? ' (saat ini)' : '' }}</option>@endforeach
                        </select>
                        @if ($report->status->next())<div class="form-text">Hanya perpindahan status sesuai alur yang tersedia.</div>@endif
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="note">Catatan Progres</label>
                        <textarea id="note" name="note" rows="4" maxlength="1000" class="form-control @error('note') is-invalid @enderror" placeholder="Tambahkan catatan perkembangan perbaikan untuk ditampilkan di lini masa...">{{ old('note') }}</textarea>
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" @checked(old('is_public', '1'))>
                        <label class="form-check-label t-body-md" for="is_public">Tampilkan catatan ke publik</label>
                        <div class="form-text">Hilangkan centang untuk catatan internal (hanya terlihat admin).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unggah Foto Progres / Setelah Perbaikan</label>
                        <select name="photo_type" class="form-select mb-2" aria-label="Jenis foto">
                            <option value="progress" @selected(old('photo_type') === 'progress')>Foto Progres Perbaikan</option>
                            <option value="after" @selected(old('photo_type') === 'after')>Foto Setelah Perbaikan</option>
                        </select>
                        <div class="sp-dropzone" data-photo-drop tabindex="0" role="button" aria-label="Pilih foto dokumentasi" style="padding:18px 12px;">
                            <i class="bi bi-image" aria-hidden="true"></i>
                            <div class="t-label-sm mt-1">Pilih foto (JPG/PNG/WebP, maks. 5 MB, maks. 3 foto)</div>
                        </div>
                        <input type="file" name="photos[]" class="d-none" accept="image/jpeg,image/png,image/webp" multiple data-photo-input data-max-files="3">
                        <div class="text-danger t-body-md mt-2 d-none" data-photo-message role="alert"></div>
                        @error('photos')<div class="text-danger t-body-md mt-2">{{ $message }}</div>@enderror
                        @foreach ($errors->get('photos.*') as $msgs)@foreach ($msgs as $m)<div class="text-danger t-body-md mt-2">{{ $m }}</div>@endforeach @endforeach
                        <div class="sp-previews" data-photo-previews></div>
                    </div>
                    <button type="submit" class="btn btn-sp w-100"><i class="bi bi-save"></i> Simpan Pembaruan Laporan</button>
                </form>
            </div>
        @endif
    </div>
</div>

@if ($report->status === \App\Enums\ReportStatus::Pending)
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.reports.reject', $report) }}" class="modal-content" data-once novalidate>
            @csrf
            <div class="modal-header">
                <h2 class="modal-title t-title-lg" id="rejectTitle">Tolak Laporan</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="reason">Alasan Penolakan <span class="text-danger">*</span></label>
                <textarea id="reason" name="reason" rows="4" maxlength="500" class="form-control @error('reason') is-invalid @enderror" placeholder="Tuliskan alasan penolakan laporan (misal: laporan duplikat atau lokasi tidak ditemukan)..." required>{{ old('reason') }}</textarea>
                @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Alasan ini dapat dilihat pelapor melalui kode pelacakan.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sp-outline" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sp-danger-solid">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>
@if ($errors->has('reason'))
    @push('scripts')<script>document.addEventListener('DOMContentLoaded',function(){new bootstrap.Modal(document.getElementById('rejectModal')).show();});</script>@endpush
@endif
@endif
@endsection
