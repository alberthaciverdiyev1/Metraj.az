<?php

namespace App\Modules\Roommate\Enums;

enum GenderPreference: string
{
    case Any = 'any';
    case Female = 'female';
    case Male = 'male';

    public function label(): string
    {
        return match ($this) {
            self::Any => 'Fərqi yoxdur (Hər kəs)',
            self::Female => 'Yalnız xanım',
            self::Male => 'Yalnız bəy',
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::Any => 'Hamı üçün',
            self::Female => 'Yalnız Xanım',
            self::Male => 'Yalnız Bəy',
        };
    }
}
