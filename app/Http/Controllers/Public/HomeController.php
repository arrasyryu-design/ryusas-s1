<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        $counts = Report::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $stats = [
            'total' => (int) $counts->sum(),
            'process' => (int) (($counts['diverifikasi'] ?? 0) + ($counts['dalam_pengerjaan'] ?? 0) + ($counts['menunggu_verifikasi'] ?? 0)),
            'done' => (int) ($counts['selesai'] ?? 0),
        ];

        $latest = Report::with('category')->publiclyVisible()->latest('verified_at')->take(3)->get();

        return view('public.home', compact('stats', 'latest'));
    }
}
