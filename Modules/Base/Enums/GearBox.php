<?php

namespace App\Enums;

enum GearBox: string
{
    case AUTOMATIC = '0x001';
    case VARIATOR = '0x002';
    case MECHANICAL = '0x003';
    case ROBOT = '0x004';
    case REDUCER = '0x005';

    public function label(): string
    {
        return match ($this) {
            GearBox::AUTOMATIC => __('Automatic'),
            GearBox::VARIATOR => __('Variator'),
            GearBox::MECHANICAL => __('Mechanical'),
            GearBox::ROBOT => __('Robot'),
            GearBox::REDUCER => __('Reducer')
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
