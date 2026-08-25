<?php

namespace App\Modules\Inquiry\Controllers;

use App\Modules\Inquiry\Services\InquiryService;
use App\Http\Controllers\Controller;
use App\Modules\Inquiry\Requests\ContactRequest;
use App\Modules\Inquiry\Requests\StoreInquiryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class InquiryController extends Controller
{
    public function __construct(
        protected InquiryService $inquiryService,
    ) {}

    public function store(StoreInquiryRequest $request): JsonResponse|RedirectResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('property.inquiry_success'),
            ]);
        }

        return back()->with('success', __('property.inquiry_success'));
    }

    /**
     * Ümumi əlaqə forması (əmlak olmadan).
     */
    public function contact(ContactRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $this->inquiryService->create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'] ?? null,
            'type' => 'contact',
            'status' => 'new',
        ]);

        $message = __('property.contact_success');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
