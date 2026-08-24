<?php

namespace App\Modules\Roommate\Enums;

enum RoommateStatus: string
{
    case Published = 'published';
    case Pending = 'pending';
    case Rejected = 'rejected';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Published => 'Dərc olunub',
            self::Pending => 'Gözləmədə',
            self::Rejected => 'İmtina edilib',
            self::Closed => 'Bağlanıb',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Published => 'success',
            self::Pending => 'warning',
            self::Rejected => 'danger',
            self::Closed => 'gray',
        };
    }
}
