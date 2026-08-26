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

use App\Modules\Shared\Services\ImageOptimizerService;

/**
 * Əmlak elanları üzərindəki bütün iş məntiqini cəmləyən servis.
 * Controller-lar Eloquent modellərinə birbaşa toxunmur, yalnız bu servisi çağırır.
 */
class PropertyService
{
    public function __construct(
        protected PropertyRepository $propertyRepository,
        protected ImageOptimizerService $imageOptimizer,
    ) {}

    public function paginate(PropertyFilterDTO $filter, int $perPage = 30): LengthAwarePaginator
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
     * Yüklənən fotoşəkilləri əmlaka əlavə edir və kiçik thumbnail-lər yaradır.
     *
     * @param  array<int, UploadedFile>  $photos
     */
    public function storeImages(Property $property, array $photos): void
    {
        foreach ($photos as $order => $photo) {
            // Şəkli "KibrisKare.com" watermark ilə yaddaşa yazırıq
            $path = $this->imageOptimizer->saveWithWatermark($photo, 'properties');
            
            // Həmin şəkildən kiçik həcmli WebP thumbnail yaradırıq (5-20 KB)
            $absolutePath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
            $thumbnailPath = $this->imageOptimizer->createThumbnail($absolutePath, 'properties/thumbnails');

            PropertyImage::create([
                'property_id' => $property->id,
                'url' => $path,
                'thumbnail_url' => $thumbnailPath,
                'sort_order' => $order,
            ]);
        }
    }

    /**
     * Yüklənən video faylını yaddaşa yazır və yolunu qaytarır.
     */
    public function storeVideo(UploadedFile $video): string
    {
        return $video->store('properties/videos', 'public');
    }
}
