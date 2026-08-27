<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Modules\Shared\Services\CurrencyService;
use App\Modules\Shared\Enums\Currency;

class UpdateCurrencyRatesCommand extends Command
{
    protected $signature = 'currency:update-rates';
    protected $description = 'Fetches live currency rates from external API and caches them forever to prevent synchronous latency in web requests.';

    public function handle(): int
    {
        $this->info('Fetching live currency rates from API...');
        
        $defaultRates = Currency::getDefaultRatesFromGbp();
        try {
            $response = Http::timeout(10)->get('https://open.er-api.com/v6/latest/GBP');
            if ($response->successful() && isset($response->json()['rates'])) {
                $apiRates = $response->json()['rates'];
                $rates = $defaultRates;

                foreach (array_keys($defaultRates) as $cur) {
                    if (isset($apiRates[$cur])) {
                        $rates[$cur] = (float) $apiRates[$cur];
                    }
                }

                Cache::forever(CurrencyService::CACHE_KEY, $rates);
                $this->info('✅ Live exchange rates updated and cached successfully.');
                return Command::SUCCESS;
            }
            
            $this->error('Failed to get rates: API returned non-successful response.');
        } catch (\Throwable $e) {
            $this->error('Failed to fetch live currency rates: ' . $e->getMessage());
        }

        // Fallback: Cache default rates if cache is completely empty
        if (!Cache::has(CurrencyService::CACHE_KEY)) {
            Cache::forever(CurrencyService::CACHE_KEY, $defaultRates);
            $this->warn('⚠️ Cached default fallback exchange rates.');
        }

        return Command::FAILURE;
    }
}
