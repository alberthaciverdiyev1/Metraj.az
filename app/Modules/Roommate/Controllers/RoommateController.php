<?php

namespace App\Modules\Roommate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Services\LocationService;
use App\Modules\Roommate\DTOs\RoommateFilterDTO;
use App\Modules\Roommate\Models\RoommateListing;
use App\Modules\Roommate\Requests\StoreRoommateListingRequest;
use App\Modules\Roommate\Services\RoommateService;
use App\Modules\Shared\Concerns\CachesGuestPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoommateController extends Controller
{
    use CachesGuestPage;

    public function __construct(
        protected RoommateService $roommateService,
        protected LocationService $locationService,
    ) {}

    public function index(Request $request): \Illuminate\Http\Response|View|JsonResponse
    {
        $filter = RoommateFilterDTO::fromArray($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            $listings = $this->roommateService->paginate($filter, 18);

            return response()->json([
                'success' => true,
                'html' => view('pages.roommates.partials.cards', compact('listings'))->render(),
                'pagination' => view('pages.roommates.partials.pagination', compact('listings'))->render(),
                'total' => $listings->total(),
            ]);
        }

        // Tam səhifə keşi (qonaqlar üçün)
        if (! $request->has('_cache_bust')) {
            return $this->cacheGuestPage($request, 'roommates_index', 60, fn () => $this->renderIndex($request, $filter));
        }

        return response($this->renderIndex($request, $filter));
    }

    protected function renderIndex(Request $request, RoommateFilterDTO $filter): string
    {
        $listings = $this->roommateService->paginate($filter, 18);
        $cities = $this->locationService->activeCities();
        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('roommates.page_title'), 'url' => null],
        ];

        return view('pages.roommates.index', compact('listings', 'cities', 'breadcrumbs', 'filter'))->render();
    }

    public function create(): View
    {
        $cities = $this->locationService->activeCities();
        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('roommates.page_title'), 'url' => route('roommates.index')],
            ['label' => __('roommates.post_roommate_ad'), 'url' => null],
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
                'message' => __('roommates.post_success'),
                'redirect' => route('roommates.show', $listing->slug),
            ]);
        }

        return redirect()->route('roommates.show', $listing->slug)->with('success', __('roommates.post_success'));
    }

    public function show(string $locale, string $slug): View
    {
        $listing = RoommateListing::published()
            ->where('slug', $slug)
            ->with(['city', 'district', 'images', 'user'])
            ->firstOrFail();

        $this->roommateService->incrementViews($listing);

        // Oxşar elanlar (eyni şəhər və ya eyni cinsiyyət tercihi)
        $similarListings = RoommateListing::published()
            ->where('id', '!=', $listing->id)
            ->where(function ($q) use ($listing) {
                $hasCity = !empty($listing->city_id);
                $gender = $listing->gender_preference?->value;
                $hasGender = !empty($gender) && $gender !== 'any';

                if ($hasCity && $hasGender) {
                    $q->where('city_id', $listing->city_id)
                      ->orWhere('gender_preference', $gender);
                } elseif ($hasCity) {
                    $q->where('city_id', $listing->city_id);
                } elseif ($hasGender) {
                    $q->where('gender_preference', $gender);
                }
            })
            ->with(['city', 'district', 'images'])
            ->latest('id')
            ->take(4)
            ->get();

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('roommates.page_title'), 'url' => route('roommates.index')],
            ['label' => $listing->title, 'url' => null],
        ];

        return view('pages.roommates.show', compact('listing', 'similarListings', 'breadcrumbs'));
    }
}
