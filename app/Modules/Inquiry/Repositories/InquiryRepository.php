<?php

namespace App\Modules\Inquiry\Repositories;

use App\Modules\Inquiry\Repositories\InquiryRepositoryInterface;
use App\Modules\Inquiry\Models\Inquiry;

class InquiryRepository implements InquiryRepositoryInterface
{
    public function create(array $data): Inquiry
    {
        return Inquiry::create($data);
    }
}
