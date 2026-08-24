<?php

namespace App\Modules\Roommate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Services\LocationService;
use App\Modules\Roommate\DTOs\RoommateFilterDTO;
use App\Modules\Roommate\Models\RoommateListing;
use App\Modules\Roommate\Requests\StoreRoommateListingRequest;
use App\Modules\Roommate\Services\RoommateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoommateController extends Controller
{
    public function __construct(
        protected RoommateService $roommateService,
        protected LocationService $locationService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $filter = RoommateFilterDTO::fromArray($request->all());
        $listings = $this->roommateService->paginate($filter, 18);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('pages.roommates.partials.cards', compact('listings'))->render(),
                'pagination' => view('pages.roommates.partials.pagination', compact('listings'))->render(),
                'total' => $listings->total(),
            ]);
        }

        $cities = $this->locationService->activeCities();
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Otaq Yoldaşı'), 'url' => null],
        ];

        return view('pages.roommates.index', compact('listings', 'cities', 'breadcrumbs', 'filter'));
    }

    public function create(): View
    {
        $cities = $this->locationService->activeCities();
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Otaq Yoldaşı'), 'url' => route('roommates.index')],
            ['label' => __('Elan Yerləşdir'), 'url' => null],
        ];

        return view('pages.roommates.create', compact('cities', 'breadcrumbs'));
    }

    public function store(StoreRoommateListingRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $images = $request->file('images', []);

        $listing = $this->roommateService->store($validated, $images);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Otaq yoldaşı elanınız uğurla yerləşdirildi!',
                'redirect' => route('roommates.show', $listing->slug),
            ]);
        }

        return redirect()->route('roommates.show', $listing->slug)->with('success', 'Otaq yoldaşı elanınız uğurla yerləşdirildi!');
    }

    public function show(string $slug): View
    {
        $listing = RoommateListing::published()
            ->where('slug', $slug)
            ->with(['city', 'district', 'images', 'user'])
            ->firstOrFail();

        $this->roommateService->incrementViews($listing);

        // Oxşar elanlar (eyni şəhər və ya eyni cinsiyyət)
        $similarListings = RoommateListing::published()
            ->where('id', '!=', $listing->id)
            ->where(function ($q) use ($listing) {
                if ($listing->city_id) {
                    $q->where('city_id', $listing->city_id);
                }
            })
            ->with(['city', 'district', 'images'])
            ->latest('id')
            ->take(4)
            ->get();

        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Otaq Yoldaşı'), 'url' => route('roommates.index')],
            ['label' => $listing->title, 'url' => null],
        ];

        return view('pages.roommates.show', compact('listing', 'similarListings', 'breadcrumbs'));
    }
}
