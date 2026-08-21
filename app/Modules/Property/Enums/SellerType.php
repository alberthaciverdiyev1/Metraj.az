<?php

namespace App\Modules\Property\Enums;

enum SellerType: string
{
    case Owner = 'owner';
    case Agency = 'agency';
    case Complex = 'complex';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Mülkiyyətçi',
            self::Agency => 'Agentlik',
            self::Complex => 'Yaşayış kompleksi',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
