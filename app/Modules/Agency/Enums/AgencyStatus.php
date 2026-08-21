<?php

namespace App\Modules\Agency\Enums;

enum AgencyStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Suspended = 'suspended';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Təsdiq Gözləyir',
            self::Active => 'Aktiv',
            self::Suspended => 'Dondurulub',
            self::Inactive => 'Qeyri-aktiv',
        };
    }
}
