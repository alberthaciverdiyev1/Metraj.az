<?php

namespace App\Core\Application\Property\Services;

use App\Core\Domain\Filter\Enums\FilterKey;
use App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption;

/**
 * Elan başlığını seçilmiş filtr seçimləri və ölçü parametrləri əsasında qurur.
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
        $buildingType = $options->first(fn ($opt) => $opt->filter?->key === FilterKey::BuildingType)?->name['az'] ?? '';

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

        return $title ?: self::FALLBACK_TITLE;
    }
}
