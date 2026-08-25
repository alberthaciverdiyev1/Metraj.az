<?php

namespace App\Modules\Property\Services;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\FilterOption;

/**
 * Elan başlığını seçilmiş filtr seçimləri və ölçü parametrləri əsasında qurur.
 * Format: [Əməliyyat (Məs: Satılır / Kirayə)], [Otaq sayı (Məs: 3+1) / Sahə], [Məkan (Location)]
 * Həm veb forması, həm də Filament paneli üçün tək qaynaqdır.
 */
class PropertyTitleBuilder
{
    public const string FALLBACK_TITLE = 'Əmlak Elanı';

    /**
     * @param array $filterOptionIds Seçilmiş filter option id'ləri
     */
    public function build(
        array $filterOptionIds,
        ?int $rooms,
        ?float $area,
        ?float $landArea,
        string $location = '',
        ?string $locale = null
    ): string {
        $locale = $locale ?: app()->getLocale();

        if (empty($filterOptionIds)) {
            return match($locale) {
                'tr' => 'Emlak İlanı',
                'en' => 'Property Listing',
                'ru' => 'Объявление о недвижимости',
                default => self::FALLBACK_TITLE,
            };
        }

        $options = FilterOption::whereIn('id', $filterOptionIds)
            ->with('filter')
            ->get();

        $propOpt = $options->first(fn ($opt) => $opt->filter?->key === FilterKey::PropertyType);
        $dealOpt = $options->first(fn ($opt) => $opt->filter?->key === FilterKey::DealType);

        $propertyType = is_array($propOpt?->name) ? ($propOpt->name[$locale] ?? $propOpt->name['tr'] ?? $propOpt->name['az'] ?? '') : ($propOpt?->name ?? '');
        $dealType = is_array($dealOpt?->name) ? ($dealOpt->name[$locale] ?? $dealOpt->name['tr'] ?? $dealOpt->name['az'] ?? '') : ($dealOpt?->name ?? '');
        $dealValue = $dealOpt?->value ?? '';
        $propValue = $propOpt?->value ?? '';

        $titleParts = [];
        $isLand = str_contains(mb_strtolower($propertyType), 'torpaq') || str_contains(mb_strtolower($propertyType), 'arsa') || str_contains($propValue, 'land');

        // 1) Əməliyyat növü (Deal Type)
        if (str_contains($dealValue, 'sale') || str_contains(mb_strtolower($dealType), 'sat') || str_contains(mb_strtolower($dealType), 'alış')) {
            $dealLabel = match($locale) {
                'tr' => 'Satılık',
                'en' => 'For Sale',
                'ru' => 'Продажа',
                default => 'Satılır',
            };
        } elseif (str_contains($dealValue, 'rent') || str_contains(mb_strtolower($dealType), 'kira') || str_contains(mb_strtolower($dealType), 'icarə')) {
            $dealLabel = match($locale) {
                'tr' => 'Kiralık',
                'en' => 'For Rent',
                'ru' => 'Аренда',
                default => 'Kirayə',
            };
        } else {
            $dealLabel = $dealType ? mb_convert_case($dealType, MB_CASE_TITLE, 'UTF-8') : 'Satılır';
        }
        $titleParts[] = $dealLabel;

        // 2) Otaq sayı (Məs: 3+1) və ya Torpaq/Sahə
        if ($rooms && ! $isLand) {
            $titleParts[] = $rooms . '+1';
        } elseif ($isLand && $landArea) {
            $unit = match($locale) {
                'tr' => 'dönüm',
                'en' => 'sot',
                'ru' => 'сот.',
                default => 'sot',
            };
            $titleParts[] = $landArea . ' ' . $unit;
        } elseif ($area) {
            $titleParts[] = round($area) . ' m²';
        } elseif ($propertyType) {
            $titleParts[] = mb_convert_case($propertyType, MB_CASE_TITLE, 'UTF-8');
        }

        // 3) Yerləşmə (Location)
        if ($location) {
            $titleParts[] = trim($location);
        }

        $title = implode(', ', array_filter($titleParts));

        return $title ?: self::FALLBACK_TITLE;
    }

    public function buildAll(
        array $filterOptionIds,
        ?int $rooms,
        ?float $area,
        ?float $landArea,
        string $location = ''
    ): array {
        $result = [];
        foreach (['az', 'tr', 'en', 'ru'] as $loc) {
            $result[$loc] = $this->build($filterOptionIds, $rooms, $area, $landArea, $location, $loc);
        }
        return $result;
    }
}
