<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class ReportAttachment extends Model
{
    public const TYPES = [
        'before' => 'Foto Kerusakan Awal',
        'progress' => 'Foto Progres Perbaikan',
        'after' => 'Foto Setelah Perbaikan',
    ];

    protected $fillable = ['report_id', 'user_id', 'file_path', 'original_name', 'attachment_type'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->attachment_type] ?? 'Dokumentasi';
    }

    /**
     * Foto disimpan di disk privat dan disajikan lewat route bertanda tangan
     * (signed URL) yang hanya dibuat oleh halaman yang berhak menampilkannya.
     */
    public function url(): string
    {
        return URL::temporarySignedRoute('media.show', now()->addHours(3), ['attachment' => $this->id]);
    }
}
