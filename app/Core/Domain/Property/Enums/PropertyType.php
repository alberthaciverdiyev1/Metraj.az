<?php

namespace App\Core\Domain\Property\Enums;

enum PropertyType: string
{
    case Apartment = 'apartment';
    case House = 'house';
    case Villa = 'villa';
    case Commercial = 'commercial';
    case Office = 'office';
    case Land = 'land';
    case Garage = 'garage';

    public function label(): string
    {
        return match ($this) {
            self::Apartment => 'Mənzil',
            self::House => 'Həyət evi',
            self::Villa => 'Villa / Bağ evi',
            self::Commercial => 'Obyekt',
            self::Office => 'Ofis',
            self::Land => 'Torpaq',
            self::Garage => 'Qaraj',
        };
    }
}
