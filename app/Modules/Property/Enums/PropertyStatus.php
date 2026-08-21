<?php

namespace App\Modules\Property\Enums;

enum PropertyStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Published = 'published';
    case Rejected = 'rejected';
    case Sold = 'sold';
    case Rented = 'rented';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Qaralama',
            self::PendingApproval => 'Təsdiq Gözləyir',
            self::Published => 'Dərc olunub',
            self::Rejected => 'İmtina edilib',
            self::Sold => 'Satılıb',
            self::Rented => 'Kirayə verilib',
            self::Archived => 'Arxivlənib',
        };
    }
}
