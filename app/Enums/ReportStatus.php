<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'menunggu';
    case Verified = 'diverifikasi';
    case InProgress = 'dalam_pengerjaan';
    case Review = 'menunggu_verifikasi';
    case Completed = 'selesai';
    case Rejected = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Verified => 'Diverifikasi',
            self::InProgress => 'Dalam Pengerjaan',
            self::Review => 'Menunggu Verifikasi',
            self::Completed => 'Selesai',
            self::Rejected => 'Ditolak',
        };
    }

    /** Nama class CSS untuk badge (lihat public/css/sipeka.css). */
    public function cssClass(): string
    {
        return 'st-' . str_replace('_', '-', $this->value);
    }

    /** Status tujuan yang diperbolehkan oleh workflow PRD. */
    public function next(): array
    {
        return match ($this) {
            self::Pending => [self::Verified, self::Rejected],
            self::Verified => [self::InProgress],
            self::InProgress => [self::Review],
            self::Review => [self::Completed, self::InProgress],
            self::Completed, self::Rejected => [],
        };
    }

    public function canMoveTo(self $to): bool
    {
        return in_array($to, $this->next(), true);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
