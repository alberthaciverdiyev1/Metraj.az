<?php

namespace App\Core\Domain\Property\Enums;

enum RepairType: string
{
    case Repaired = 'repaired';
    case Unrepaired = 'unrepaired';

    public function label(): string
    {
        return match ($this) {
            self::Repaired => 'Təmirli',
            self::Unrepaired => 'Təmirsiz',
        };
    }
}
