<?php

namespace App\Modules\Shared\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    /**
     * Exchange rates are cached for 12 hours (cache key versioned).
     */
    public const string CACHE_KEY = 'currency_rates_gbp_v1';

    public const int CACHE_TTL_SECONDS = 3600 * 12;

    /**
     * Standard fallback rates per 1 GBP if network is unavailable
     */
    public const array DEFAULT_RATES_FROM_GBP = [
        'GBP' => 1.0,
        'USD' => 1.30,
        'EUR' => 1.18,
        'AZN' => 2.21,
        'TRY' => 44.50,
        'RUB' => 120.0,
        'AED' => 4.77,
    ];

    /**
     * Get currency symbols and labels
     */
    public function getCurrencies(): array
    {
        return [
            'GBP' => ['symbol' => '£', 'label' => 'Funt Sterlinq (GBP)', 'code' => 'GBP'],
            'USD' => ['symbol' => '$', 'label' => 'ABŞ Dolları (USD)', 'code' => 'USD'],
            'EUR' => ['symbol' => '€', 'label' => 'Avro (EUR)', 'code' => 'EUR'],
            'AZN' => ['symbol' => '₼', 'label' => 'Azərbaycan Manatı (AZN)', 'code' => 'AZN'],
            'TRY' => ['symbol' => '₺', 'label' => 'Türk Lirəsi (TRY)', 'code' => 'TRY'],
            'RUB' => ['symbol' => '₽', 'label' => 'Rusiya Rublu (RUB)', 'code' => 'RUB'],
            'AED' => ['symbol' => 'د.إ', 'label' => 'BƏƏ Dirhəmi (AED)', 'code' => 'AED'],
        ];
    }

    /**
     * Get live daily exchange rates with caching and graceful fallback
     */
    public function getRatesFromGbp(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            try {
                $response = Http::timeout(3)->get('https://open.er-api.com/v6/latest/GBP');
                if ($response->successful() && isset($response->json()['rates'])) {
                    $apiRates = $response->json()['rates'];
                    $rates = self::DEFAULT_RATES_FROM_GBP;

                    foreach (array_keys(self::DEFAULT_RATES_FROM_GBP) as $cur) {
                        if (isset($apiRates[$cur])) {
                            $rates[$cur] = (float) $apiRates[$cur];
                        }
                    }

                    return $rates;
                }
            } catch (\Throwable $e) {
                // Fallback to default
            }

            return self::DEFAULT_RATES_FROM_GBP;
        });
    }

    /**
     * Convert an amount from GBP to all target currencies
     */
    public function convertFromGbp(float $amountGbp): array
    {
        $rates = $this->getRatesFromGbp();
        $converted = [];

        foreach ($rates as $cur => $rate) {
            $val = $amountGbp * $rate;
            // Round nicely
            $converted[$cur] = $val >= 1000 ? round($val) : round($val, 2);
        }

        return $converted;
    }
}
