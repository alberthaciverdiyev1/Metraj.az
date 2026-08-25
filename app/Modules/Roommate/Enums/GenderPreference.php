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
            self::Any => __('roommates.gender_any_label'),
            self::Female => __('roommates.gender_female_label'),
            self::Male => __('roommates.gender_male_label'),
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::Any => __('roommates.gender_any_badge'),
            self::Female => __('roommates.gender_female_label'),
            self::Male => __('roommates.gender_male_label'),
        };
    }
}
