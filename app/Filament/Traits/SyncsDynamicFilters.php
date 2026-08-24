<?php

namespace App\Filament\Traits;

trait SyncsDynamicFilters
{
    protected function afterCreate(): void
    {
        $this->syncAfterSave();
    }

    protected function afterSave(): void
    {
        $this->syncAfterSave();
    }

    /**
     * Yaratma və ya yeniləmədən sonra ortaq sinxronizasiya addımları:
     * filtr seçimləri, şəkillər və title/slug yeniləməsi.
     */
    protected function syncAfterSave(): void
    {
        $this->syncDynamicFilters();
        $this->syncPropertyImages();

        // buildTitleFromOptions verilənlər bazasındakı əlaqələrə əsasən title qurur;
        // bu səbəbdən əlaqələr bazaya yazıldıqdan sonra title və slug yenilənir.
        $record = $this->getRecord();
        $rawState = $this->form->getRawState();
        $filterOptionIds = [];
        foreach ($rawState as $key => $value) {
            if (str_starts_with($key, 'filter_') && !empty($value)) {
                $filterOptionIds[] = (int) $value;
            }
        }

        $rooms = $record->rooms;
        $area = $record->area;
        $record->loadMissing(['city', 'district']);
        $location = $record->district?->name['az'] ?? ($record->city?->name['az'] ?? '');
        $landArea = $record->land_area;

        $title = $this->buildTitleFromOptions($filterOptionIds, $rooms, $area, $landArea, $location);
        $record->update([
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . $record->id,
        ]);
    }

    protected function syncPropertyImages(): void
    {
        $record = $this->getRecord();
        $rawState = $this->form->getRawState();

        if (array_key_exists('uploaded_images', $rawState)) {
            $images = $rawState['uploaded_images'] ?? [];
            if (! is_array($images)) {
                $images = empty($images) ? [] : [$images];
            }

            $record->images()->delete();
            $sort = 0;
            foreach ($images as $imgItem) {
                if (empty($imgItem)) continue;
                $url = is_array($imgItem) ? ($imgItem['url'] ?? reset($imgItem)) : $imgItem;
                if (!empty($url)) {
                    $record->images()->create([
                        'url' => $url,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // İlkin olaraq boş keçməsin deyə müvəqqəti title və slug veririk, afterCreate-də dəqiq olanla əvəz edəcəyik.
        // Sabit müvəqqəti slug istifadə olunur — qeyd yaradılan kimi dərhal dəqiq slug ilə əvəz edilir.
        $data['title'] = 'Müvəqqəti Elan Başlığı';
        $data['slug'] = 'temp-slug-' . now()->timestamp;

        $user = \Illuminate\Support\Facades\Auth::user();

        // Elanı yaradan istifadəçi həmişə user_id kimi qeyd olunur (admin panel daxil)
        $data['user_id'] = $data['user_id'] ?? $user?->id;

        // Agentlik panelində sahiblik və satıcı növü avtomatik təyin edilir:
        // - agency_id → istifadəçinin tenant agentliyi
        // - agent_id → istifadəçinin öz rieltor profili (varsa)
        // - seller_type → Agentlik (agency) və ya Mülkiyyətçi (owner)
        // - status → təsdiq gözləyir
        if (\Filament\Facades\Filament::getCurrentPanel()?->getId() === 'agency') {
            $data['agency_id'] = $user?->tenantAgency()?->id;
            $data['agent_id'] = $user?->agent?->id;
            $data['seller_type'] = ($user?->tenantAgency() || $user?->agent)
                ? \App\Modules\Property\Enums\SellerType::Agency->value
                : \App\Modules\Property\Enums\SellerType::Owner->value;
            $data['status'] = \App\Modules\Property\Enums\PropertyStatus::PendingApproval->value;
        }

        // Multi-currency price packaging
        $mainCurrency = strtoupper($data['currency'] ?? 'GBP');
        $data['currency'] = $mainCurrency;
        $currencyService = app(\App\Modules\Shared\Services\CurrencyService::class);

        $enteredPrice = (float) ($data['price_input'] ?? $data['price_gbp'] ?? $data['price'] ?? 0);
        $basePrice = $currencyService->getBaseGbp($enteredPrice, $mainCurrency);
        $data['price'] = $basePrice;

        $prices = $currencyService->convertFromCurrency($enteredPrice, $mainCurrency);
        if (empty($data['auto_convert_currency'])) {
            if (!empty($data['price_gbp'])) $prices['GBP'] = (float) $data['price_gbp'];
            if (!empty($data['price_usd'])) $prices['USD'] = (float) $data['price_usd'];
            if (!empty($data['price_eur'])) $prices['EUR'] = (float) $data['price_eur'];
            if (!empty($data['price_azn'])) $prices['AZN'] = (float) $data['price_azn'];
            if (!empty($data['price_try'])) $prices['TRY'] = (float) $data['price_try'];
            if (!empty($data['price_rub'])) $prices['RUB'] = (float) $data['price_rub'];
            if (!empty($data['price_aed'])) $prices['AED'] = (float) $data['price_aed'];
        }
        $prices[$mainCurrency] = $enteredPrice;
        $prices['GBP'] = $basePrice;
        $data['prices'] = $prices;

        // Əgər "Draft" düyməsi sıxılıbsa status Qaralama (Draft) olaraq qeyd edilir
        if ($this->isDraft ?? false) {
            $data['status'] = \App\Modules\Property\Enums\PropertyStatus::Draft->value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Multi-currency price packaging
        $mainCurrency = strtoupper($data['currency'] ?? 'GBP');
        $data['currency'] = $mainCurrency;
        $currencyService = app(\App\Modules\Shared\Services\CurrencyService::class);

        $enteredPrice = (float) ($data['price_input'] ?? $data['price_gbp'] ?? $data['price'] ?? 0);
        $basePrice = $currencyService->getBaseGbp($enteredPrice, $mainCurrency);
        $data['price'] = $basePrice;

        $prices = $currencyService->convertFromCurrency($enteredPrice, $mainCurrency);
        if (empty($data['auto_convert_currency'])) {
            if (!empty($data['price_gbp'])) $prices['GBP'] = (float) $data['price_gbp'];
            if (!empty($data['price_usd'])) $prices['USD'] = (float) $data['price_usd'];
            if (!empty($data['price_eur'])) $prices['EUR'] = (float) $data['price_eur'];
            if (!empty($data['price_azn'])) $prices['AZN'] = (float) $data['price_azn'];
            if (!empty($data['price_try'])) $prices['TRY'] = (float) $data['price_try'];
            if (!empty($data['price_rub'])) $prices['RUB'] = (float) $data['price_rub'];
            if (!empty($data['price_aed'])) $prices['AED'] = (float) $data['price_aed'];
        }
        $prices[$mainCurrency] = $enteredPrice;
        $prices['GBP'] = $basePrice;
        $data['prices'] = $prices;

        return $data;
    }

    protected function syncDynamicFilters(): void
    {
        $record = $this->getRecord();
        $rawState = $this->form->getRawState();

        $filterOptionIds = [];
        foreach ($rawState as $key => $value) {
            if (str_starts_with($key, 'filter_') && !empty($value)) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $filterOptionIds[] = (int) $val;
                    }
                } else {
                    $filterOptionIds[] = (int) $value;
                }
            }
        }

        if (!empty($filterOptionIds)) {
            $record->filterOptions()->sync($filterOptionIds);
        }
    }

    protected function buildTitleFromOptions(array $filterOptionIds, ?int $rooms, ?float $area, ?float $landArea, string $location = ''): string
    {
        return app(\App\Modules\Property\Services\PropertyTitleBuilder::class)
            ->build($filterOptionIds, $rooms, $area, $landArea, $location);
    }
}
