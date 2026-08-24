<?php

namespace App\Modules\Roommate\Enums;

enum RoommateListingType: string
{
    case HaveRoom = 'have_room';
    case NeedRoom = 'need_room';

    public function label(): string
    {
        return match ($this) {
            self::HaveRoom => 'Evim var, otaq yoldaşı axtarıram',
            self::NeedRoom => 'Ev axtarıram, ortaq ev tutmaq istəyirəm',
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::HaveRoom => 'Otaq verilir',
            self::NeedRoom => 'Otaq axtarır',
        };
    }
}
