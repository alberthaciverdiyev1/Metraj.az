<?php

namespace App\Modules\Roommate\Enums;

enum OccupationPreference: string
{
    case Any = 'any';
    case Student = 'student';
    case Working = 'working';

    public function label(): string
    {
        return match ($this) {
            self::Any => 'Fərqi yoxdur',
            self::Student => 'Yalnız tələbə',
            self::Working => 'Yalnız işləyən',
        };
    }
}
