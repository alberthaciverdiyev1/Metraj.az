<?php

namespace Modules\Base\Enums;

enum RoomCount: string
{
    case ONE_BEDROOM = '0x001';
    case TWO_BEDROOM = '0x002';
    case THREE_BEDROOM = '0x003';
    case FOUR_BEDROOM = '0x004';
    case FIVE_AND_MORE = '0x005';

    public function label(): string
    {
        return match ($this) {
            RoomCount::ONE_BEDROOM => __('One Bedroom'),
            RoomCount::TWO_BEDROOM => __('Two Bedroom'),
            RoomCount::THREE_BEDROOM => __('Three Bedroom'),
            RoomCount::FOUR_BEDROOM => __('Four Bedroom'),
            RoomCount::FIVE_AND_MORE => __('Five and More'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
