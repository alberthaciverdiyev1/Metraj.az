<?php

namespace App\Modules\Inquiry\Services;

use App\Modules\Inquiry\Repositories\InquiryRepository;
use App\Modules\Inquiry\Models\Inquiry;

/**
 * Müştəri müraciətləri (lead) ilə bağlı iş məntiqi.
 */
class InquiryService
{
    public function __construct(
        protected InquiryRepository $inquiryRepository,
    ) {}

    public function create(array $data): Inquiry
    {
        return $this->inquiryRepository->create($data);
    }
}
