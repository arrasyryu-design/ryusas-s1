<?php

namespace App\Enums;

enum ReportPriority: string
{
    case Low = 'rendah';
    case Medium = 'sedang';
    case High = 'tinggi';
    case Urgent = 'darurat';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Rendah',
            self::Medium => 'Sedang',
            self::High => 'Tinggi',
            self::Urgent => 'Darurat',
        };
    }

    public function cssClass(): string
    {
        return 'pr-' . $this->value;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
