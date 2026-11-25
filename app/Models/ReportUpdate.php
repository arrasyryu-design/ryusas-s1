<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportUpdate extends Model
{
    protected $fillable = ['report_id', 'user_id', 'old_status', 'new_status', 'note', 'is_public'];

    protected function casts(): array
    {
        return [
            'old_status' => ReportStatus::class,
            'new_status' => ReportStatus::class,
            'is_public' => 'boolean',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
