<?php

namespace App\Core\Domain\Property\Enums;

enum DealType: string
{
    case Sale = 'sale';
    case RentMonthly = 'rent_monthly';
    case RentDaily = 'rent_daily';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'Satış',
            self::RentMonthly => 'Aylıq Kirayə',
            self::RentDaily => 'Günlük Kirayə',
        };
    }
}
