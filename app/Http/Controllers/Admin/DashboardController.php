<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = Report::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $stats = [
            'total' => (int) $counts->sum(),
            'menunggu' => (int) ($counts['menunggu'] ?? 0),
            'diverifikasi' => (int) ($counts['diverifikasi'] ?? 0),
            'dalam_pengerjaan' => (int) ($counts['dalam_pengerjaan'] ?? 0),
            'menunggu_verifikasi' => (int) ($counts['menunggu_verifikasi'] ?? 0),
            'selesai' => (int) ($counts['selesai'] ?? 0),
            'ditolak' => (int) ($counts['ditolak'] ?? 0),
        ];

        $needsAttention = Report::with('category')
            ->where(function ($q) {
                $q->whereIn('status', ['menunggu', 'menunggu_verifikasi'])
                    ->orWhere(fn ($w) => $w->whereIn('priority', ['tinggi', 'darurat'])
                        ->whereNotIn('status', ['selesai', 'ditolak']));
            })
            ->orderByRaw("FIELD(priority, 'darurat', 'tinggi', 'sedang', 'rendah')")
            ->latest()
            ->take(8)
            ->get();

        $activities = ActivityLog::with('user')->latest()->latest('id')->take(6)->get();

        return view('admin.dashboard', compact('stats', 'needsAttention', 'activities'));
    }
}
