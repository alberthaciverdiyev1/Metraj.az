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
            self::Buy => __('requests.type_buy_label'),
            self::RentMonthly => __('requests.type_rent_label'),
            self::RentDaily => __('requests.type_daily_label'),
            self::RoommateHave => __('requests.type_roommate_have_label'),
            self::RoommateNeed => __('requests.type_roommate_need_label'),
        };
    }

    public function badgeLabel(): string
    {
        return match ($this) {
            self::Buy => __('requests.badge_buy'),
            self::RentMonthly => __('requests.badge_rent'),
            self::RentDaily => __('requests.badge_daily'),
            self::RoommateHave => __('requests.badge_roommate_have'),
            self::RoommateNeed => __('requests.badge_roommate_need'),
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
