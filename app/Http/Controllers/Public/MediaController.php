<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ReportAttachment;
use Illuminate\Support\Facades\Storage;

/**
 * Menyajikan foto dari disk privat. Route ini wajib bertanda tangan (middleware "signed"),
 * dan tanda tangan hanya dibuat oleh halaman yang berhak menampilkan fotonya.
 */
class MediaController extends Controller
{
    public function __invoke(ReportAttachment $attachment)
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($attachment->file_path), 404);

        return $disk->response($attachment->file_path, null, [
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
