<?php

namespace App\Models;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'report_code', 'category_id', 'reporter_name', 'reporter_class', 'reporter_contact',
        'location', 'title', 'description', 'priority', 'status', 'verified_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
            'priority' => ReportPriority::class,
            'verified_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ReportUpdate::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    /** Hanya laporan yang sudah diverifikasi Admin boleh tampil di halaman publik. */
    public function scopePubliclyVisible($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function isPubliclyVisible(): bool
    {
        return $this->verified_at !== null;
    }
}
