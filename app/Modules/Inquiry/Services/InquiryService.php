<?php

namespace App\Modules\Inquiry\Services;

use App\Modules\Inquiry\Repositories\InquiryRepositoryInterface;
use App\Modules\Inquiry\Models\Inquiry;

/**
 * Müştəri müraciətləri (lead) ilə bağlı iş məntiqi.
 */
class InquiryService
{
    public function __construct(
        protected InquiryRepositoryInterface $inquiryRepository,
    ) {}

    public function create(array $data): Inquiry
    {
        return $this->inquiryRepository->create($data);
    }
}
