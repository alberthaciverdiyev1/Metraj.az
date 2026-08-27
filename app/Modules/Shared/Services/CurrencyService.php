<?php

namespace App\Modules\Shared\Services;

use App\Modules\Shared\Enums\Currency;
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
     * Get currency symbols and labels from Currency Enum
     */
    public function getCurrencies(): array
    {
        return Currency::getCurrenciesList();
    }

    /**
     * Get live daily exchange rates with caching and graceful fallback
     */
    public function getRatesFromGbp(): array
    {
        return Cache::get(self::CACHE_KEY) ?? Currency::getDefaultRatesFromGbp();
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

    /**
     * Get equivalent amount in GBP from any source currency
     */
    public function getBaseGbp(float $amount, string $fromCurrency = 'GBP'): float
    {
        $fromCurrency = strtoupper(trim($fromCurrency));
        if ($fromCurrency === 'GBP' || $amount <= 0) {
            return $amount;
        }

        $rates = $this->getRatesFromGbp();
        $rate = (float) ($rates[$fromCurrency] ?? 1.0);

        return $rate > 0 ? round($amount / $rate, 2) : $amount;
    }

    /**
     * Convert an amount from any currency to all target currencies
     */
    public function convertFromCurrency(float $amount, string $fromCurrency = 'GBP'): array
    {
        $fromCurrency = strtoupper(trim($fromCurrency));
        $baseGbp = $this->getBaseGbp($amount, $fromCurrency);
        $converted = $this->convertFromGbp($baseGbp);

        // Ensure the source currency has the exact entered amount
        $converted[$fromCurrency] = $amount;

        return $converted;
    }
}
