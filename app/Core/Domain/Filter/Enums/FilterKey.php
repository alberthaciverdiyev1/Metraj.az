<?php

namespace App\Core\Domain\Filter\Enums;

enum FilterKey: string
{
    // Əmlak və Əməliyyat növləri
    case DealType = 'deal_type';
    case PropertyType = 'property_type';
    case BuildingType = 'building_type';
    case RepairType = 'repair_type';

    // Əlavə Texniki və Rahatlıq Parametrləri
    case HeatingSystem = 'heating_system';
    case BuildingProject = 'building_project';
    case WindowView = 'window_view';
    case Balcony = 'balcony';
    case WaterSupply = 'water_supply';

    /**
     * Açara uyğun insan oxunaqlı Azərbaycan dilində başlıq
     */
    public function label(): string
    {
        return match ($this) {
            self::DealType => 'Alqı-satqı növü',
            self::PropertyType => 'Əmlakın növü',
            self::BuildingType => 'Tikilinin növü',
            self::RepairType => 'Təmir vəziyyəti',
            self::HeatingSystem => 'İstilik sistemi',
            self::BuildingProject => 'Binanın layihəsi',
            self::WindowView => 'Pəncərə baxışı',
            self::Balcony => 'Balkon növü',
            self::WaterSupply => 'Su təchizatı',
        };
    }

    /**
     * Filament Select komponentləri üçün açar-dəyər cütlükləri
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $key) => [$key->value => "{$key->label()} ({$key->value})"])
            ->toArray();
    }
}
