<?php

namespace App\Modules\PropertyRequest\Enums;

enum RequestType: string
{
    case Buy = 'buy';
    case RentMonthly = 'rent_monthly';
    case RentDaily = 'rent_daily';
    case RoommateHave = 'roommate_have';
    case RoommateNeed = 'roommate_need';

    public function label(): string
    {
        return match ($this) {
            self::Buy => 'Ev / Əmlak Almaq İstəyirəm',
            self::RentMonthly => 'Kirayə Ev Axtarıram',
            self::RentDaily => 'Günlük Ev Axtarıram',
            self::RoommateHave => 'Evim var, otaq yoldaşı axtarıram',
            self::RoommateNeed => 'Ev axtarıram, birgə qalmağa yoldaş axtarıram',
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::Buy => 'Almaq istəyir',
            self::RentMonthly => 'Kirayə axtarır',
            self::RentDaily => 'Günlük axtarır',
            self::RoommateHave => 'Otaq verir',
            self::RoommateNeed => 'Otaq axtarır',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Buy => 'bg-emerald-600 text-white',
            self::RentMonthly => 'bg-blue-600 text-white',
            self::RentDaily => 'bg-amber-600 text-white',
            self::RoommateHave => 'bg-purple-600 text-white',
            self::RoommateNeed => 'bg-indigo-600 text-white',
        };
    }
}
