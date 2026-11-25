<?php

namespace App\Services;

use App\Models\Report;

class ReportCodeGenerator
{
    /** Contoh: SKL-20261124-A8F3D2 (6 karakter heksadesimal acak, dijamin unik). */
    public function generate(): string
    {
        do {
            $code = 'SKL-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while (Report::where('report_code', $code)->exists());

        return $code;
    }
}
