<?php

namespace App\Core\Domain\Property\Enums;

enum BuildingType: string
{
    case NewBuilding = 'new_building';
    case OldBuilding = 'old_building';

    public function label(): string
    {
        return match ($this) {
            self::NewBuilding => 'Yeni tikili',
            self::OldBuilding => 'Köhnə tikili',
        };
    }
}
