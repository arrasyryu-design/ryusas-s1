<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function __invoke(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('kode', '')));
        $searched = $code !== '';
        $report = null;
        $updates = collect();

        if ($searched && strlen($code) <= 40 && preg_match('/^[A-Z0-9\-]+$/', $code)) {
            $report = Report::with(['category', 'attachments'])->where('report_code', $code)->first();
            if ($report) {
                // Hanya catatan publik; nama pelapor & catatan internal tidak pernah dikirim ke view.
                $updates = $report->updates()->where('is_public', true)->latest()->latest('id')->get();
            }
        }

        return view('public.tracking', compact('code', 'searched', 'report', 'updates'));
    }
}
