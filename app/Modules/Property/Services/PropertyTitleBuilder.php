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
        string $location = ''
    ): string {
        if (empty($filterOptionIds)) {
            return self::FALLBACK_TITLE;
        }

        $options = FilterOption::whereIn('id', $filterOptionIds)
            ->with('filter')
            ->get();

        $propertyType = $options->first(fn ($opt) => $opt->filter?->key === FilterKey::PropertyType)?->name['az'] ?? '';
        $dealType = $options->first(fn ($opt) => $opt->filter?->key === FilterKey::DealType)?->name['az'] ?? '';

        $titleParts = [];
        $isLand = str_contains(mb_strtolower($propertyType), 'torpaq');

        // 1) Əməliyyat növü (Öncə: Satılır / Kirayə)
        if ($dealType) {
            $dealLower = mb_strtolower($dealType);
            if (str_contains($dealLower, 'satış') || str_contains($dealLower, 'satılır') || str_contains($dealLower, 'satılıq') || $dealLower === 'alış') {
                $titleParts[] = 'Satılır';
            } elseif (str_contains($dealLower, 'kirayə') || str_contains($dealLower, 'icarə')) {
                $titleParts[] = 'Kirayə';
            } else {
                $titleParts[] = mb_convert_case($dealType, MB_CASE_TITLE, 'UTF-8');
            }
        } else {
            $titleParts[] = 'Satılır';
        }

        // 2) Otaq sayı (Məs: 3+1) və ya Torpaq/Sahə
        if ($rooms && ! $isLand) {
            $titleParts[] = $rooms . '+1';
        } elseif ($isLand && $landArea) {
            $titleParts[] = $landArea . ' sot';
        } elseif ($area) {
            $titleParts[] = round($area) . ' m²';
        } elseif ($propertyType) {
            $titleParts[] = mb_convert_case($propertyType, MB_CASE_TITLE, 'UTF-8');
        }

        // 3) Yerləşmə (Location - Məs: Yasamal r., Bakı)
        if ($location) {
            $titleParts[] = trim($location);
        }

        $title = implode(', ', array_filter($titleParts));

        return $title ?: self::FALLBACK_TITLE;
    }
}
