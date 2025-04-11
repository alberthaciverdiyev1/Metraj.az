<?php

namespace App\Enums;

enum Gear: string
{
    case FRONT = '0x001';
    case BACK = '0x002';
    case BOTH = '0x003';

    public function label(): string
    {
        return match ($this) {
            self::FRONT => __('Front'),
            self::BACK => __('Back'),
            self::BOTH => __('Both'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
