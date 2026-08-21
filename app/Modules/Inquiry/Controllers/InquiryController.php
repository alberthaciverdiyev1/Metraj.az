<?php

namespace App\Modules\Inquiry\Controllers;

use App\Modules\Inquiry\Services\InquiryService;
use App\Http\Controllers\Controller;
use App\Modules\Inquiry\Requests\StoreInquiryRequest;
use Illuminate\Http\RedirectResponse;

class InquiryController extends Controller
{
    public function __construct(
        protected InquiryService $inquiryService,
    ) {}

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->inquiryService->create([
            'property_id' => $validated['property_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'new',
        ]);

        return back()->with('success', 'Müraciətiniz uğurla qəbul edildi! Ən qısa zamanda sizinlə əlaqə saxlanılacaq.');
    }
}
