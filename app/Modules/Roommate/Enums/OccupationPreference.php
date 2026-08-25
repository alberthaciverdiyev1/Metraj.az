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
            self::Any => __('roommates.occupation_any'),
            self::Student => __('roommates.occupation_student'),
            self::Working => __('roommates.occupation_working'),
        };
    }
}
