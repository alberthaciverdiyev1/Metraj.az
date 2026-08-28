<?php

namespace App\Modules\PropertyRequest\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Services\LocationService;
use App\Modules\PropertyRequest\DTOs\PropertyRequestFilterDTO;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use App\Modules\PropertyRequest\Requests\StorePropertyRequestRequest;
use App\Modules\PropertyRequest\Services\PropertyRequestService;
use App\Modules\Shared\Concerns\CachesGuestPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyRequestController extends Controller
{
    use CachesGuestPage;

    public function __construct(
        protected PropertyRequestService $requestService,
        protected LocationService $locationService,
    ) {}

    public function index(Request $request): \Illuminate\Http\Response|View|JsonResponse
    {
        $rawMinPrice = $request->input('min_price', $request->input('minPrice'));
        $rawMaxPrice = $request->input('max_price', $request->input('maxPrice'));
        if ($rawMinPrice !== null && $rawMinPrice !== '' && $rawMaxPrice !== null && $rawMaxPrice !== '') {
            if ((float) $rawMinPrice > (float) $rawMaxPrice) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('listing.min_price_greater_than_max'),
                        'errors' => ['price' => [__('listing.min_price_greater_than_max')]],
                    ], 422);
                }
            }
        }

        $rawMinArea = $request->input('min_area', $request->input('minArea'));
        $rawMaxArea = $request->input('max_area', $request->input('maxArea'));
        if ($rawMinArea !== null && $rawMinArea !== '' && $rawMaxArea !== null && $rawMaxArea !== '') {
            if ((float) $rawMinArea > (float) $rawMaxArea) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('listing.min_area_greater_than_max'),
                        'errors' => ['area' => [__('listing.min_area_greater_than_max')]],
                    ], 422);
                }
            }
        }

        $filter = PropertyRequestFilterDTO::fromArray($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            $requests = $this->requestService->paginate($filter, 18);

            return response()->json([
                'success' => true,
                'html' => view('pages.requests.partials.cards', compact('requests'))->render(),
                'pagination' => view('pages.requests.partials.pagination', ['requests' => $requests])->render(),
                'total' => $requests->total(),
            ]);
        }

        // Tam səhifə keşi (qonaqlar üçün) — filtrsiz standart siyahı sürətli qaytarılır
        if (! $request->has('_cache_bust')) {
            return $this->cacheGuestPage($request, 'requests_index', 60, fn () => $this->renderIndex($request, $filter));
        }

        return response($this->renderIndex($request, $filter));
    }

    protected function renderIndex(Request $request, PropertyRequestFilterDTO $filter): string
    {
        $requests = $this->requestService->paginate($filter, 18);
        $cities = $this->locationService->activeCities();
        $buildingTypes = $this->locationService->propertyTypeOptions();

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('requests.page_title'), 'url' => null],
        ];

        return view('pages.requests.index', compact('requests', 'cities', 'buildingTypes', 'breadcrumbs', 'filter'))->render();
    }

    public function create(): View
    {
        $cities = $this->locationService->activeCities();
        $buildingTypes = $this->locationService->propertyTypeOptions();

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('requests.requests'), 'url' => route('requests.index')],
            ['label' => __('requests.post_request_title'), 'url' => null],
        ];

        return view('pages.requests.create', compact('cities', 'buildingTypes', 'breadcrumbs'));
    }

    public function store(StorePropertyRequestRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $images = $request->file('images', []);

        $propertyRequest = $this->requestService->store($validated, $images);

        $successMsg = __('requests.request_success');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'redirect' => route('requests.show', $propertyRequest->slug),
            ]);
        }

        return redirect()->route('requests.show', $propertyRequest->slug)->with('success', $successMsg);
    }

    public function show(string $locale, string $slug): View
    {
        $propertyRequest = PropertyRequest::published()
            ->where('slug', $slug)
            ->with(['city', 'district', 'images', 'user'])
            ->firstOrFail();

        $this->requestService->incrementViews($propertyRequest);

        // Similar requests
        $similarRequests = PropertyRequest::published()
            ->where('id', '!=', $propertyRequest->id)
            ->where('request_type', $propertyRequest->request_type)
            ->with(['city', 'district', 'images'])
            ->latest('id')
            ->take(4)
            ->get();

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('requests.requests'), 'url' => route('requests.index')],
            ['label' => $propertyRequest->title, 'url' => null],
        ];

        return view('pages.requests.show', compact('propertyRequest', 'similarRequests', 'breadcrumbs'));
    }
}
