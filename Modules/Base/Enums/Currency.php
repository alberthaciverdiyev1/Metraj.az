<?php

namespace Modules\Base\Enums;

enum Currency:string
{
    case AZN = '0x001';
    case USD = '0x002';
    case EUR = '0x003';

    public function label(): string
    {
        return match ($this) {
            self::AZN => __('AZN'),
            self::USD => __('USD'),
            self::EUR => __('EUR'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
