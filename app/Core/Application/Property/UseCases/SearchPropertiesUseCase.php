<?php

namespace App\Core\Application\Property\UseCases;

use App\Core\Application\Property\DTOs\PropertyFilterDTO;
use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchPropertiesUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository
    ) {}

    public function execute(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->paginate($filter, $perPage);
    }
}
