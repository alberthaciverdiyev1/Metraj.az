<?php

namespace App\Filament\Traits;

trait SyncsDynamicFilters
{
    protected function afterCreate(): void
    {
        $this->syncDynamicFilters();
        $this->syncPropertyImages();
        
        // Həmçinin yaratdıqdan sonra title və slug-ı bazaya yazırıq (çünki buildTitleFromOptions
        // verilənlər bazasındakı əlaqələrə əsasən title qurur və yaratdıqdan sonra əlaqələr artıq bazada olur)
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

    protected function afterSave(): void
    {
        $this->syncDynamicFilters();
        $this->syncPropertyImages();

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
        // İlkin olaraq boş keçməsin deyə müvəqqəti title və slug veririk, afterCreate-də dəqiq olanla əvəz edəcəyik
        $data['title'] = 'Müvəqqəti Elan Başlığı';
        $data['slug'] = 'temp-slug-' . rand(10000, 99999);

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
                ? \App\Core\Domain\Property\Enums\SellerType::Agency->value
                : \App\Core\Domain\Property\Enums\SellerType::Owner->value;
            $data['status'] = \App\Core\Domain\Property\Enums\PropertyStatus::PendingApproval->value;
        }

        // Multi-currency price packaging
        if (isset($data['price_gbp']) || isset($data['price'])) {
            $basePrice = (float) ($data['price_gbp'] ?? $data['price'] ?? 0);
            $data['price'] = $basePrice;
            $data['currency'] = 'GBP';

            $prices = \App\Core\Application\Currency\CurrencyService::convertFromGbp($basePrice);
            if (empty($data['auto_convert_currency'])) {
                if (!empty($data['price_usd'])) $prices['USD'] = (float) $data['price_usd'];
                if (!empty($data['price_eur'])) $prices['EUR'] = (float) $data['price_eur'];
                if (!empty($data['price_azn'])) $prices['AZN'] = (float) $data['price_azn'];
                if (!empty($data['price_try'])) $prices['TRY'] = (float) $data['price_try'];
                if (!empty($data['price_rub'])) $prices['RUB'] = (float) $data['price_rub'];
                if (!empty($data['price_aed'])) $prices['AED'] = (float) $data['price_aed'];
            }
            $prices['GBP'] = $basePrice;
            $data['prices'] = $prices;
        }

        // Əgər "Draft" düyməsi sıxılıbsa status Qaralama (Draft) olaraq qeyd edilir
        if ($this->isDraft ?? false) {
            $data['status'] = \App\Core\Domain\Property\Enums\PropertyStatus::Draft->value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Multi-currency price packaging
        if (isset($data['price_gbp']) || isset($data['price'])) {
            $basePrice = (float) ($data['price_gbp'] ?? $data['price'] ?? 0);
            $data['price'] = $basePrice;
            $data['currency'] = 'GBP';

            $prices = \App\Core\Application\Currency\CurrencyService::convertFromGbp($basePrice);
            if (empty($data['auto_convert_currency'])) {
                if (!empty($data['price_usd'])) $prices['USD'] = (float) $data['price_usd'];
                if (!empty($data['price_eur'])) $prices['EUR'] = (float) $data['price_eur'];
                if (!empty($data['price_azn'])) $prices['AZN'] = (float) $data['price_azn'];
                if (!empty($data['price_try'])) $prices['TRY'] = (float) $data['price_try'];
                if (!empty($data['price_rub'])) $prices['RUB'] = (float) $data['price_rub'];
                if (!empty($data['price_aed'])) $prices['AED'] = (float) $data['price_aed'];
            }
            $prices['GBP'] = $basePrice;
            $data['prices'] = $prices;
        }

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
        if (empty($filterOptionIds)) {
            return 'Əmlak Elanı';
        }

        $options = \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::whereIn('id', $filterOptionIds)
            ->with('filter')
            ->get();

        $propertyType = $options->first(fn ($opt) => $opt->filter?->key === \App\Core\Domain\Filter\Enums\FilterKey::PropertyType)?->name['az'] ?? '';
        $dealType = $options->first(fn ($opt) => $opt->filter?->key === \App\Core\Domain\Filter\Enums\FilterKey::DealType)?->name['az'] ?? '';
        $buildingType = $options->first(fn ($opt) => $opt->filter?->key === \App\Core\Domain\Filter\Enums\FilterKey::BuildingType)?->name['az'] ?? '';

        $titleParts = [];

        // E.g. "Yasamal"
        if ($location) {
            $titleParts[] = $location;
        }

        // E.g. "3 otaqlı"
        if ($rooms && strtolower($propertyType) !== 'torpaq') {
            $titleParts[] = $rooms . ' otaqlı';
        }

        // E.g. "yeni tikili"
        if ($buildingType) {
            $titleParts[] = mb_strtolower($buildingType);
        }

        // E.g. "mənzil" or "torpaq"
        if ($propertyType) {
            $titleParts[] = mb_strtolower($propertyType);
        }

        // Area: "120 m²" or "10 sot"
        if (strtolower($propertyType) === 'torpaq' && $landArea) {
            $titleParts[] = $landArea . ' sot';
        } elseif ($area) {
            $titleParts[] = $area . ' m²';
        }

        // Deal type: "satılır" or "kirayə verilir"
        if ($dealType) {
            $dealLower = mb_strtolower($dealType);
            if (str_contains($dealLower, 'satış') || str_contains($dealLower, 'satılır') || $dealLower === 'alış') {
                $titleParts[] = 'satılır';
            } elseif (str_contains($dealLower, 'kirayə') || str_contains($dealLower, 'icarə')) {
                $titleParts[] = 'kirayə verilir';
            } else {
                $titleParts[] = $dealLower;
            }
        }

        $title = implode(' ', array_filter($titleParts));
        
        return $title ?: 'Əmlak Elanı';
    }
}
