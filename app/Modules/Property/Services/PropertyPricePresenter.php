<?php

namespace App\Modules\Property\Services;

use App\Modules\Shared\Services\CurrencyService;
use App\Modules\Property\Models\Property;

/**
 * Elan qiymətinin göstərilməsi üçün təqdimat (presentation) servisi.
 * Model daxilindəki qiymət formatlama məntiqini view katmanına xidmət edən
 * ayrıca sinifə köçürür.
 */
class PropertyPricePresenter
{
    public function __construct(
        protected CurrencyService $currencyService,
    ) {}

    /**
     * Aktiv valyutaya uyğun formatlaşdırılmış qiyməti qaytarır.
     *
     * @return array{amount: float, formatted: string, currency: string, symbol: string}
     */
    public function display(Property $property, ?string $targetCurrency = null): array
    {
        $currency = strtoupper($targetCurrency ?: session('currency', 'GBP'));
        $prices = $property->prices ?? [];
        $symbol = match ($currency) {
            'GBP' => '£',
            'USD' => '$',
            'EUR' => '€',
            'AZN' => '₼',
            'TRY' => '₺',
            'RUB' => '₽',
            'AED' => 'AED',
            default => $currency,
        };

        if (! empty($prices[$currency])) {
            $amount = (float) $prices[$currency];
        } else {
            $baseGbp = (float) $property->price;
            $rates = $this->currencyService->getRatesFromGbp();
            $rate = $rates[$currency] ?? 1.0;
            $amount = $baseGbp * $rate;
        }

        return [
            'amount' => $amount,
            'formatted' => number_format($amount, 0, '.', ' '),
            'currency' => $currency,
            'symbol' => $symbol,
        ];
    }
}
