<?php

namespace App\Modules\Shared\Enums;

enum SupportedLocale: string
{
    case TR = 'tr';
    case AZ = 'az';
    case EN = 'en';
    case RU = 'ru';

    public function label(): string
    {
        return match ($this) {
            self::TR => 'Türkçe',
            self::AZ => 'Azərbaycanca',
            self::EN => 'English',
            self::RU => 'Русский',
        };
    }

    public function flag(): string
    {
        return match ($this) {
            self::TR => '🇹🇷',
            self::AZ => '🇦🇿',
            self::EN => '🇬🇧',
            self::RU => '🇷🇺',
        };
    }

    public function shortCode(): string
    {
        return strtoupper($this->value);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $locale): bool
    {
        return in_array($locale, self::values(), true);
    }

    public static function getList(): array
    {
        $list = [];
        foreach (self::cases() as $locale) {
            $list[$locale->value] = [
                'code' => $locale->value,
                'name' => $locale->label(),
                'flag' => $locale->flag(),
                'label' => $locale->shortCode(),
            ];
        }
        return $list;
    }
}
