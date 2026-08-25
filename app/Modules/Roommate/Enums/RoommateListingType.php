<?php

namespace App\Modules\Roommate\Enums;

enum RoommateListingType: string
{
    case HaveRoom = 'have_room';
    case NeedRoom = 'need_room';

    public function label(): string
    {
        return match ($this) {
            self::HaveRoom => __('roommates.have_room_full'),
            self::NeedRoom => __('roommates.need_room_full'),
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::HaveRoom => __('roommates.have_room'),
            self::NeedRoom => __('roommates.need_room'),
        };
    }
}
