<?php

namespace App\Modules\Property\Enums;

enum RentType: string
{
    case Monthly = 'monthly';
    case Daily = 'daily';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'Aylıq',
            self::Daily => 'Günlük',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromSlug(string $slug): ?self
    {
        return match (strtolower(trim($slug))) {
            'monthly', 'ayliq', 'aylik' => self::Monthly,
            'daily', 'gunluk', 'gundelik' => self::Daily,
            default => null,
        };
    }
}
