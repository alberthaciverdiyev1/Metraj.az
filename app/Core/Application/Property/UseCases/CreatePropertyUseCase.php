<?php

namespace App\Core\Application\Property\UseCases;

use App\Core\Application\Property\DTOs\CreatePropertyDTO;
use App\Core\Domain\Property\Entities\Property;
use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;

class CreatePropertyUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository
    ) {}

    public function execute(CreatePropertyDTO $dto): Property
    {
        return $this->propertyRepository->create($dto);
    }
}
