@extends('layouts.public')
@section('title', 'Laporkan Kerusakan')

@section('content')
<div class="sp-wrap sp-section" style="max-width: 860px;">
    <div class="mb-4">
        <h1 class="t-headline-lg mb-2">Formulir Pelaporan Kerusakan Fasilitas</h1>
        <p class="t-body-lg sp-muted mb-0">Sampaikan kondisi fasilitas yang rusak tanpa perlu masuk akun. Identitas pelapor bersifat opsional dan hanya dapat dilihat oleh admin sekolah.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-octagon"></i> Laporan belum dapat dikirim. Periksa isian berikut:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" data-once novalidate>
        @csrf

        <div class="sp-card sp-card-pad mb-4">
            <h2 class="t-title-lg mb-3">Identitas Pelapor (Opsional)</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="reporter_name">Nama Pelapor</label>
                    <input type="text" id="reporter_name" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror" maxlength="100" placeholder="Contoh: Raka Pratama" value="{{ old('reporter_name', $member->name ?? '') }}">
                    @error('reporter_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="reporter_class">Kelas</label>
                    <input type="text" id="reporter_class" name="reporter_class" class="form-control @error('reporter_class') is-invalid @enderror" maxlength="50" placeholder="Contoh: XI IPA 2" value="{{ old('reporter_class', $member->class_or_position ?? '') }}">
                    @error('reporter_class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="reporter_contact">Kontak (WA / Email)</label>
                    <input type="text" id="reporter_contact" name="reporter_contact" class="form-control @error('reporter_contact') is-invalid @enderror" maxlength="100" placeholder="Contoh: 0812-xxxx-xxxx" value="{{ old('reporter_contact', $member->email ?? '') }}">
                    @error('reporter_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="sp-card sp-card-pad mb-4">
            <h2 class="t-title-lg mb-3">Detail Kerusakan</h2>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label" for="category_id">Kategori Kerusakan <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih kategori kerusakan</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="location">Lokasi Fasilitas <span class="text-danger">*</span></label>
                    <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" maxlength="200" placeholder="Contoh: Gedung B Lt. 2, Ruang X Merdeka 1" value="{{ old('location') }}" required>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="title">Judul Laporan <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" maxlength="150" placeholder="Tuliskan ringkasan singkat kerusakan" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                    <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror" maxlength="3000" placeholder="Jelaskan kondisi kerusakan, sejak kapan terjadi, dan dampaknya terhadap kegiatan belajar (minimal 20 karakter)." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="sp-card sp-card-pad mb-4" data-photo-zone>
            <h2 class="t-title-lg mb-3">Unggah Foto Kerusakan <span class="text-danger">*</span></h2>
            <div class="sp-dropzone" data-photo-drop tabindex="0" role="button" aria-label="Pilih foto kerusakan">
                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                <div class="t-label-lg mt-2">Pilih foto dari perangkat atau seret ke sini</div>
                <div class="form-text">Format JPG, JPEG, PNG, atau WebP · maksimal 3 foto · 5 MB per foto.</div>
            </div>
            <input type="file" name="photos[]" id="photos" class="d-none" accept="image/jpeg,image/png,image/webp" multiple data-photo-input data-max-files="3">
            <div class="text-danger t-body-md mt-2 d-none" data-photo-message role="alert"></div>
            @error('photos')<div class="text-danger t-body-md mt-2">{{ $message }}</div>@enderror
            @foreach ($errors->get('photos.*') as $msgs)@foreach ($msgs as $m)<div class="text-danger t-body-md mt-2">{{ $m }}</div>@endforeach @endforeach
            <div class="sp-previews" data-photo-previews></div>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <p class="t-label-sm sp-muted mb-0">Kolom bertanda (*) wajib diisi sebelum mengirim laporan.</p>
            <button type="submit" class="btn btn-sp d-inline-flex align-items-center gap-2 px-4 py-2"><i class="bi bi-send" aria-hidden="true"></i> Kirim Laporan</button>
        </div>
    </form>
</div>
@endsection
