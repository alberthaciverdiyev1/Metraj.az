<?php

enum FuelType:string
{
    case GASOLINE = '0x001';
    case DIESEL = '0x002';
    case GAS  = '0x003';
    case ELECTRIC = '0x004';
    case HYBRID = '0x005';
    case PLUG_IN_HYBRID = '0x006';
    case HYDROGEN = '0x007';
    case NONE  = '0x008';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::GASOLINE => __('Gasoline'),
            self::DIESEL => __('Diesel'),
            self::GAS => __('gas'),
            self::ELECTRIC => __('Electric'),
            self::HYBRID => __('Hybrid'),
            self::PLUG_IN_HYBRID => __('Plug-in hybrid'),
            self::HYDROGEN => __('Hydrogen'),
            self::NONE => __('None'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
