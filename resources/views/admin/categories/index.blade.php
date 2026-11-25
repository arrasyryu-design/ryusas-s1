@extends('layouts.admin')
@section('title', 'Kategori Kerusakan')
@section('page_title', 'Kategori Kerusakan')

@section('content')
<p class="sp-muted mb-3">Kelola daftar kategori kerusakan yang tersedia pada formulir pelaporan. Kategori tidak dihapus permanen; nonaktifkan agar tidak muncul di formulir.</p>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="sp-card">
            <div class="sp-table-wrap">
                <table class="sp-table">
                    <thead><tr><th>Nama Kategori</th><th>Deskripsi Singkat</th><th>Jumlah Laporan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @foreach ($categories as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->name }}</td>
                            <td class="sp-muted">{{ $c->description ?: '—' }}</td>
                            <td>{{ $c->reports_count }}</td>
                            <td><span class="sp-badge {{ $c->is_active ? 'st-selesai' : 'pr-rendah' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sp-outline btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-action="{{ route('admin.categories.update', $c) }}" data-name="{{ $c->name }}" data-description="{{ $c->description }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.categories.toggle', $c) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $c->is_active ? 'btn-sp-danger' : 'btn-sp-outline' }}">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="sp-card sp-card-pad">
            <h3 class="sp-panel-title mb-3">Tambah Kategori Kerusakan</h3>
            <form method="POST" action="{{ route('admin.categories.store') }}" data-once novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" maxlength="100" placeholder="Contoh: Jaringan Internet & Wi-Fi" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi Singkat</label>
                    <textarea id="description" name="description" rows="3" maxlength="500" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan jenis fasilitas yang termasuk dalam kategori ini...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-sp w-100">Simpan Kategori</button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="" class="modal-content" id="editForm" data-once novalidate>
            @csrf @method('PUT')
            <div class="modal-header">
                <h2 class="modal-title t-title-lg" id="editTitle">Ubah Kategori</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" for="edit_name">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-control" maxlength="100" required>
                </div>
                <div>
                    <label class="form-label" for="edit_description">Deskripsi Singkat</label>
                    <textarea id="edit_description" name="description" rows="3" maxlength="500" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sp-outline" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sp">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('editModal').addEventListener('show.bs.modal', function (e) {
    var b = e.relatedTarget;
    document.getElementById('editForm').action = b.dataset.action;
    document.getElementById('edit_name').value = b.dataset.name || '';
    document.getElementById('edit_description').value = b.dataset.description || '';
});
</script>
@endpush
@endsection
