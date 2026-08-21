<?php

namespace App\Modules\Inquiry\Repositories;

use App\Modules\Inquiry\Models\Inquiry;

interface InquiryRepositoryInterface
{
    public function create(array $data): Inquiry;
}
