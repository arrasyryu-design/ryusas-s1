@extends('layouts.admin')
@section('title', 'Riwayat Aktivitas')
@section('page_title', 'Riwayat Aktivitas')

@section('content')
@php
    $labels = ['login' => 'Login', 'logout' => 'Logout', 'verify' => 'Verifikasi', 'reject' => 'Penolakan', 'status' => 'Perubahan status', 'priority' => 'Perubahan prioritas', 'note' => 'Catatan', 'attachment' => 'Upload dokumentasi', 'confirm_completion' => 'Konfirmasi selesai', 'category' => 'Kategori'];
@endphp
<div class="sp-card">
    @if ($logs->isEmpty())
        <div class="sp-empty"><i class="bi bi-clock-history"></i><p class="mt-2 mb-0">Belum ada aktivitas tercatat.</p></div>
    @else
        <div class="sp-table-wrap">
            <table class="sp-table">
                <thead><tr><th>Waktu</th><th>Admin</th><th>Aktivitas</th><th>Keterangan</th></tr></thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->translatedFormat('d M Y · H:i') }}</td>
                        <td class="text-nowrap">{{ $log->user?->name ?? 'Sistem' }}</td>
                        <td><span class="sp-badge st-diverifikasi">{{ $labels[$log->action] ?? $log->action }}</span></td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end p-3 border-top" style="border-color: var(--sp-outline) !important;">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
