<?php

namespace App\Modules\Shared\Enums;

enum Currency: string
{
    case GBP = 'GBP';
    case USD = 'USD';
    case EUR = 'EUR';
    case AZN = 'AZN';
    case TRY = 'TRY';
    case RUB = 'RUB';
    case AED = 'AED';

    public function symbol(): string
    {
        return match ($this) {
            self::GBP => '£',
            self::USD => '$',
            self::EUR => '€',
            self::AZN => '₼',
            self::TRY => '₺',
            self::RUB => '₽',
            self::AED => 'د.إ',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::GBP => 'Funt Sterlinq (GBP)',
            self::USD => 'ABŞ Dolları (USD)',
            self::EUR => 'Avro (EUR)',
            self::AZN => 'Azərbaycan Manatı (AZN)',
            self::TRY => 'Türk Lirəsi (TRY)',
            self::RUB => 'Rusiya Rublu (RUB)',
            self::AED => 'BƏƏ Dirhəmi (AED)',
        };
    }

    public function defaultRateFromGbp(): float
    {
        return match ($this) {
            self::GBP => 1.0,
            self::USD => 1.30,
            self::EUR => 1.18,
            self::AZN => 2.21,
            self::TRY => 44.50,
            self::RUB => 120.0,
            self::AED => 4.77,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $currency): bool
    {
        return in_array(strtoupper($currency), self::values(), true);
    }

    public static function getSymbols(): array
    {
        $symbols = [];
        foreach (self::cases() as $currency) {
            $symbols[$currency->value] = $currency->symbol();
        }
        return $symbols;
    }

    public static function getCurrenciesList(): array
    {
        $list = [];
        foreach (self::cases() as $currency) {
            $list[$currency->value] = [
                'code' => $currency->value,
                'symbol' => $currency->symbol(),
                'label' => $currency->label(),
            ];
        }
        return $list;
    }

    public static function getDefaultRatesFromGbp(): array
    {
        $rates = [];
        foreach (self::cases() as $currency) {
            $rates[$currency->value] = $currency->defaultRateFromGbp();
        }
        return $rates;
    }
}
