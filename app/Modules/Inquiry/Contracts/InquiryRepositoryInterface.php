<?php

namespace App\Modules\Inquiry\Contracts;

use App\Modules\Inquiry\Models\Inquiry;

interface InquiryRepositoryInterface
{
    public function create(array $data): Inquiry;
}
