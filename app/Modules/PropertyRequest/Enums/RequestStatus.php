<?php

namespace App\Modules\PropertyRequest\Enums;

enum RequestStatus: string
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
}
