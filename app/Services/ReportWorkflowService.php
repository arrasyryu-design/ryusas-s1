<?php

namespace App\Services;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportUpdate;
use App\Models\User;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Semua perubahan laporan lewat sini, supaya alur status (PRD bagian 6),
 * riwayat perubahan, dan log aktivitas selalu konsisten.
 */
class ReportWorkflowService
{
    public function __construct(private ReportCodeGenerator $codes)
    {
    }

    /** Dipakai form publik (tanpa login). */
    public function createReport(array $data, array $photos): Report
    {
        return DB::transaction(function () use ($data, $photos) {
            $report = Report::create($data + [
                'report_code' => $this->codes->generate(),
                'priority' => ReportPriority::Medium,
                'status' => ReportStatus::Pending,
            ]);

            ReportUpdate::create([
                'report_id' => $report->id,
                'user_id' => null,
                'old_status' => null,
                'new_status' => ReportStatus::Pending,
                'note' => 'Laporan diterima oleh sistem dan menunggu peninjauan admin.',
                'is_public' => true,
            ]);

            $this->storePhotos($report, $photos, 'before', null);

            return $report;
        });
    }

    public function verify(Report $report, User $admin, ReportPriority $priority): void
    {
        DB::transaction(function () use ($report, $admin, $priority) {
            $r = Report::lockForUpdate()->findOrFail($report->id);

            if (! $r->status->canMoveTo(ReportStatus::Verified)) {
                throw new DomainException('Hanya laporan berstatus Menunggu yang dapat diverifikasi.');
            }

            $old = $r->status;
            $r->update([
                'status' => ReportStatus::Verified,
                'priority' => $priority,
                'verified_at' => now(),
            ]);

            ReportUpdate::create([
                'report_id' => $r->id,
                'user_id' => $admin->id,
                'old_status' => $old,
                'new_status' => ReportStatus::Verified,
                'note' => 'Laporan diverifikasi oleh admin dengan prioritas ' . $priority->label() . '.',
                'is_public' => true,
            ]);

            ActivityLogger::log('verify', "Memverifikasi laporan {$r->report_code} (prioritas {$priority->label()})", $r, $admin->id);
        });
    }

    public function reject(Report $report, User $admin, string $reason): void
    {
        DB::transaction(function () use ($report, $admin, $reason) {
            $r = Report::lockForUpdate()->findOrFail($report->id);

            if (! $r->status->canMoveTo(ReportStatus::Rejected)) {
                throw new DomainException('Hanya laporan berstatus Menunggu yang dapat ditolak.');
            }

            $old = $r->status;
            $r->update(['status' => ReportStatus::Rejected]);

            ReportUpdate::create([
                'report_id' => $r->id,
                'user_id' => $admin->id,
                'old_status' => $old,
                'new_status' => ReportStatus::Rejected,
                'note' => $reason,
                'is_public' => true, // alasan penolakan dapat dilihat pelapor lewat kode pelacakan
            ]);

            ActivityLogger::log('reject', "Menolak laporan {$r->report_code}: {$reason}", $r, $admin->id);
        });
    }

    /**
     * Pembaruan status / prioritas / catatan / foto dokumentasi.
     * Mengembalikan true jika ada sesuatu yang berubah.
     *
     * @param UploadedFile[] $photos
     */
    public function update(
        Report $report,
        User $admin,
        ?ReportStatus $newStatus,
        ReportPriority $priority,
        ?string $note,
        bool $isPublic,
        array $photos = [],
        string $photoType = 'progress'
    ): bool {
        return DB::transaction(function () use ($report, $admin, $newStatus, $priority, $note, $isPublic, $photos, $photoType) {
            $r = Report::lockForUpdate()->findOrFail($report->id);
            $changed = false;
            $noteSaved = false;
            $note = $note !== null && trim($note) !== '' ? trim($note) : null;

            if ($newStatus && $newStatus !== $r->status) {
                if (! $r->status->canMoveTo($newStatus)) {
                    throw new DomainException(
                        "Status tidak dapat diubah dari {$r->status->label()} ke {$newStatus->label()}."
                    );
                }

                $old = $r->status;
                $r->status = $newStatus;
                if ($newStatus === ReportStatus::Completed) {
                    $r->completed_at = now();
                }

                ReportUpdate::create([
                    'report_id' => $r->id,
                    'user_id' => $admin->id,
                    'old_status' => $old,
                    'new_status' => $newStatus,
                    'note' => $note,
                    'is_public' => $isPublic,
                ]);

                $label = $newStatus === ReportStatus::Completed ? 'confirm_completion' : 'status';
                ActivityLogger::log($label, "Mengubah status {$r->report_code} dari {$old->label()} ke {$newStatus->label()}", $r, $admin->id);
                $changed = $noteSaved = true;
            }

            if ($priority !== $r->priority) {
                $oldPriority = $r->priority;
                $r->priority = $priority;
                ActivityLogger::log('priority', "Mengubah prioritas {$r->report_code} dari {$oldPriority->label()} ke {$priority->label()}", $r, $admin->id);
                $changed = true;
            }

            if ($note && ! $noteSaved) {
                ReportUpdate::create([
                    'report_id' => $r->id,
                    'user_id' => $admin->id,
                    'old_status' => $r->status,
                    'new_status' => $r->status,
                    'note' => $note,
                    'is_public' => $isPublic,
                ]);
                ActivityLogger::log('note', "Menambahkan catatan pada {$r->report_code}", $r, $admin->id);
                $changed = true;
            }

            $r->save();

            if (count($photos) > 0) {
                $count = $this->storePhotos($r, $photos, $photoType, $admin->id);
                ActivityLogger::log('attachment', "Mengunggah {$count} foto dokumentasi untuk {$r->report_code}", $r, $admin->id);
                $changed = true;
            }

            return $changed;
        });
    }

    /** @param UploadedFile[] $photos */
    public function storePhotos(Report $report, array $photos, string $type, ?int $userId): int
    {
        $count = 0;
        foreach ($photos as $photo) {
            if (! $photo instanceof UploadedFile) {
                continue;
            }

            ReportAttachment::create([
                'report_id' => $report->id,
                'user_id' => $userId,
                'file_path' => $photo->store("reports/{$report->id}", 'local'),
                'original_name' => mb_substr(basename($photo->getClientOriginalName()), 0, 190),
                'attachment_type' => $type,
            ]);
            $count++;
        }

        return $count;
    }
}
