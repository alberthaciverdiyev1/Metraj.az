<?php

namespace App\Modules\Property\Repositories;

use App\Modules\Property\DTOs\CreatePropertyDTO;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryInterface
{
    public function findById(int $id): ?Property;

    public function findBySlug(string $slug): ?Property;

    public function findByCode(string $code): ?Property;

    public function findPublishedBySlug(string $slug): ?Property;

    public function create(CreatePropertyDTO $dto): Property;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function paginate(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator;

    /**
     * Oxşar elanlar: eyni əmlak növü və ya eyni şəhər/rayon, çatışmayan hissə son elanlarla tamamlanır.
     *
     * @return Collection<int, Property>
     */
    public function similar(Property $property, int $limit = 3): Collection;

    /**
     * @return Collection<int, Property>
     */
    public function getFeatured(int $limit = 6): Collection;

    /**
     * @return Collection<int, Property>
     */
    public function getVip(int $limit = 6): Collection;

    public function incrementViews(int $id): void;
}
