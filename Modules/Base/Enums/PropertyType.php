<?php

namespace Modules\Base\Enums;

enum PropertyType: int
{
    case APARTMENT = 0x001;
    case NEW_BUILDING = 0x002;
    case OLD_BUILDING = 0x003;
    case COUNTRY_OR_YARD_HOUSE = 0x004;
    case OFFICE = 0x005;
    case GARAGE = 0x006;
    case LAND = 0x007;
    case OBJECT = 0x008;



    public function label(): string
    {
        return match ($this) {
            self::APARTMENT => __('Apartment'),
            self::NEW_BUILDING => __('New Building'),
            self::OLD_BUILDING => __('Old Building'),
            self::COUNTRY_OR_YARD_HOUSE => __('Country or Yard House'),
            self::OFFICE => __('Office'),
            self::GARAGE => __('Garage'),
            self::LAND => __('Land'),
            self::OBJECT => __('Object'),

        };
    }

    public function value(): int
    {
        return $this->value;
    }
}
