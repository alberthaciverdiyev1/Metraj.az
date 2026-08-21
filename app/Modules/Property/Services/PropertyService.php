<?php

namespace App\Modules\Property\Services;

use App\Modules\Property\DTOs\CreatePropertyDTO;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Repositories\PropertyRepository;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

/**
 * Əmlak elanları üzərindəki bütün iş məntiqini cəmləyən servis.
 * Controller-lar Eloquent modellərinə birbaşa toxunmur, yalnız bu servisi çağırır.
 */
class PropertyService
{
    public function __construct(
        protected PropertyRepository $propertyRepository,
    ) {}

    public function paginate(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->paginate($filter, $perPage);
    }

    public function findPublishedBySlug(string $slug): ?Property
    {
        return $this->propertyRepository->findPublishedBySlug($slug);
    }

    public function similar(Property $property, int $limit = 3): Collection
    {
        return $this->propertyRepository->similar($property, $limit);
    }

    public function featured(int $limit = 6): Collection
    {
        return $this->propertyRepository->getFeatured($limit);
    }

    public function vip(int $limit = 6): Collection
    {
        return $this->propertyRepository->getVip($limit);
    }

    public function incrementViews(int $id): void
    {
        $this->propertyRepository->incrementViews($id);
    }

    public function create(CreatePropertyDTO $dto): Property
    {
        return $this->propertyRepository->create($dto);
    }

    /**
     * 6 rəqəmli unikal elan kodu yaradır (slug və elan kodu üçün).
     */
    public function generateCode(): string
    {
        return Property::generateUniqueCode();
    }

    /**
     * Premium elanları (VIP / Featured) filtrələyir.
     *
     * @return Collection<int, Property>
     */
    public function filterPremium(LengthAwarePaginator $paginator): Collection
    {
        return $paginator->getCollection()
            ->filter(fn (Property $property) => $property->is_vip || $property->is_featured);
    }

    /**
     * Seçilmiş əmlak növü filtr seçiminin torpaq (land) olub-olmadığını yoxlayır.
     */
    public function isLandOption(\App\Modules\Location\Models\FilterOption $option): bool
    {
        return str_contains(mb_strtolower($option->name['az'] ?? $option->value), 'torpaq');
    }

    /**
     * Yüklənən fotoşəkilləri əmlaka əlavə edir.
     *
     * @param  array<int, UploadedFile>  $photos
     */
    public function storeImages(Property $property, array $photos): void
    {
        foreach ($photos as $order => $photo) {
            $path = $photo->store('properties', 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'url' => $path,
                'sort_order' => $order,
            ]);
        }
    }
}
