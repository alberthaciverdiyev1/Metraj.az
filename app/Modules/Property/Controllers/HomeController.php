<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Location\Models\City;
use App\Modules\Location\Services\LocationService;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Enums\DealType;
use App\Modules\Property\Models\QuickSearch;
use App\Modules\Property\Services\PropertyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    use \App\Modules\Shared\Concerns\CachesGuestPage;

    public function __construct(
        protected PropertyService $propertyService,
        protected LocationService $locationService,
    ) {}

    public function __invoke(Request $request, ?string $first = null, ?string $second = null, ?string $third = null)
    {
        // When accessed via localized route group /{locale}/...
        if ($first !== null && in_array(strtolower($first), ['az', 'en', 'ru', 'tr'], true)) {
            $first = $second;
            $second = $third;
            $third = func_num_args() > 4 ? func_get_arg(4) : null;
        }

        // AJAX istekleri her zaman canlı veri döndürür
        if ($request->ajax() || $request->wantsJson()) {
            return $this->ajaxListing($request, $first, $second, $third);
        }

        if (! $request->has('_cache_bust')) {
            return $this->cacheGuestPage($request, 'home_index_' . app()->getLocale(), 300, fn () => $this->renderListingPage($request, $first, $second, $third));
        }

        return response($this->renderListingPage($request, $first, $second, $third));
    }

    /**
     * AJAX (filtr/paginator) sorğuları — canlı məlumat qaytarır.
     */
    protected function ajaxListing(Request $request, ?string $first, ?string $second, ?string $third)
    {
        if ($validationErrors = $this->validateSearchRanges($request)) {
            return response()->json([
                'success' => false,
                'message' => reset($validationErrors)[0] ?? __('validation.invalid'),
                'errors' => $validationErrors,
            ], 422);
        }

        if ($first !== null) {
            $this->applyPathFilters($request, $first, $second, $third);
        }

        $perPage = (int) (\App\Modules\Shared\Models\SiteSetting::current()->items_per_page ?? 30);
        $perPage = $perPage > 0 ? $perPage : 30;

        $filter = PropertyFilterDTO::fromArray($request->all());
        $properties = $this->propertyService->paginate($filter, $perPage);

        return response()->json([
            'properties' => view('pages.property.partials.cards', compact('properties'))->render(),
            'pagination' => view('pages.property.partials.pagination', compact('properties'))->render(),
            'total' => $properties->total(),
        ]);
    }

    /**
     * Listing səhifəsini render edib HTML qaytarır.
     */
    protected function renderListingPage(Request $request, ?string $first, ?string $second, ?string $third): string
    {
        $validationErrors = $this->validateSearchRanges($request);

        $currentQuickSearch = null;
        if ($first !== null) {
            $currentQuickSearch = $this->applyPathFilters($request, $first, $second, $third);
        }

        $perPage = (int) (\App\Modules\Shared\Models\SiteSetting::current()->items_per_page ?? 30);
        $perPage = $perPage > 0 ? $perPage : 30;

        $filter = PropertyFilterDTO::fromArray($request->all());
        $properties = $this->propertyService->paginate($filter, $perPage);

        $cities = $this->locationService->activeCities();
        $buildingTypes = $this->locationService->propertyTypeOptions();
        $dynamicFilters = $this->locationService->dynamicFilters();
        $popularSearches = QuickSearch::popular()->limit(15)->get();

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => $currentQuickSearch ? $currentQuickSearch->localized_title : __('listing.all')],
        ];

        $pageTitle = $currentQuickSearch ? $currentQuickSearch->localized_title : null;
        $metaDescription = $currentQuickSearch ? $currentQuickSearch->localized_meta_description : null;

        return view('pages.property.list', compact(
            'properties',
            'cities',
            'buildingTypes',
            'dynamicFilters',
            'popularSearches',
            'currentQuickSearch',
            'breadcrumbs',
            'pageTitle',
            'metaDescription',
            'validationErrors'
        ))->with('css', ['listing.css'])->render();
    }

    /**
     * Min/Max aralıq filtrlərini yoxlayır (Min > Maks olmamalıdır)
     */
    protected function validateSearchRanges(Request $request): ?array
    {
        $errors = [];

        // Qiymət yoxlaması
        $rawMinPrice = $request->input('min_price', $request->input('minPrice'));
        $rawMaxPrice = $request->input('max_price', $request->input('maxPrice'));
        if ($rawMinPrice !== null && $rawMinPrice !== '' && $rawMaxPrice !== null && $rawMaxPrice !== '') {
            $minP = (float) str_replace([' ', ','], ['', '.'], (string) $rawMinPrice);
            $maxP = (float) str_replace([' ', ','], ['', '.'], (string) $rawMaxPrice);
            if ($minP > $maxP) {
                $errors['price'] = [__('listing.min_price_greater_than_max')];
            }
        }

        // Sahə yoxlaması
        $rawMinArea = $request->input('min_area', $request->input('minArea'));
        $rawMaxArea = $request->input('max_area', $request->input('maxArea'));
        if ($rawMinArea !== null && $rawMinArea !== '' && $rawMaxArea !== null && $rawMaxArea !== '') {
            $minA = (float) str_replace([' ', ','], ['', '.'], (string) $rawMinArea);
            $maxA = (float) str_replace([' ', ','], ['', '.'], (string) $rawMaxArea);
            if ($minA > $maxA) {
                $errors['area'] = [__('listing.min_area_greater_than_max')];
            }
        }

        // Torpaq sahəsi yoxlaması
        $rawMinLand = $request->input('min_land_area', $request->input('fieldAreaMin'));
        $rawMaxLand = $request->input('max_land_area', $request->input('fieldAreaMax'));
        if ($rawMinLand !== null && $rawMinLand !== '' && $rawMaxLand !== null && $rawMaxLand !== '') {
            $minL = (float) str_replace([' ', ','], ['', '.'], (string) $rawMinLand);
            $maxL = (float) str_replace([' ', ','], ['', '.'], (string) $rawMaxLand);
            if ($minL > $maxL) {
                $errors['land_area'] = [__('listing.min_land_area_greater_than_max')];
            }
        }

        // Mərtəbə yoxlaması
        $rawMinFloor = $request->input('min_floor', $request->input('floorMin'));
        $rawMaxFloor = $request->input('max_floor', $request->input('floorMax'));
        if ($rawMinFloor !== null && $rawMinFloor !== '' && $rawMaxFloor !== null && $rawMaxFloor !== '') {
            $minF = (float) str_replace([' ', ','], ['', '.'], (string) $rawMinFloor);
            $maxF = (float) str_replace([' ', ','], ['', '.'], (string) $rawMaxFloor);
            if ($minF > $maxF) {
                $errors['floor'] = [__('listing.min_floor_greater_than_max')];
            }
        }

        return !empty($errors) ? $errors : null;
    }

    /**
     * SEO dostu URL segmentlərini və ya QuickSearch presetini filtrə çevirir.
     */
    protected function applyPathFilters(Request $request, string $first, ?string $second, ?string $third): ?QuickSearch
    {
        // 1. Populyar axtarış / SEO Teqi yoxlanışı (/axtaris/{slug} və ya /search/{slug})
        if (in_array($first, ['axtaris', 'search'], true) && $second !== null) {
            $quickSearch = QuickSearch::where('slug', $second)->where('is_active', true)->first();
            if ($quickSearch) {
                QuickSearch::withoutTimestamps(fn () => $quickSearch->increment('view_count'));
                $request->merge($quickSearch->toQueryParams());
                return $quickSearch;
            }
            abort(404);
        }

        // 2. Birbaşa slug yoxlanışı (məs: /{slug})
        $quickSearch = QuickSearch::where('slug', $first)->where('is_active', true)->first();
        if ($quickSearch && $second === null) {
            QuickSearch::withoutTimestamps(fn () => $quickSearch->increment('view_count'));
            $request->merge($quickSearch->toQueryParams());
            return $quickSearch;
        }

        // 3. Şəhər və Əməliyyat URL-ləri
        $city = City::where('slug', $first)->where('is_active', true)->first();

        if ($city) {
            $request->merge(['cityId' => $city->id]);

            if ($second !== null) {
                $this->applyDealSegment($request, $second, $third);
            } elseif ($third !== null) {
                abort(404);
            }

            return null;
        }

        $this->applyDealSegment($request, $first, $second);
        return null;
    }

    protected function applyDealSegment(Request $request, string $deal, ?string $rent): void
    {
        $saleMap = ['satilik', 'satis', 'satiq', 'sale'];
        $rentMap = ['kira', 'kiralik', 'kiraye', 'kiraya', 'rent'];
        $rentMonthlyMap = ['ayliq', 'aylik', 'monthly'];
        $rentDailyMap = ['gunluk', 'gundelik', 'daily'];

        if (in_array($deal, $saleMap, true)) {
            if ($rent !== null) {
                abort(404);
            }
            $request->merge(['deal_type' => DealType::Sale->value]);

            return;
        }

        if (in_array($deal, $rentMap, true)) {
            if ($rent !== null) {
                if (in_array($rent, $rentMonthlyMap, true)) {
                    $request->merge(['deal_type' => DealType::RentMonthly->value]);
                } elseif (in_array($rent, $rentDailyMap, true)) {
                    $request->merge(['deal_type' => DealType::RentDaily->value]);
                } else {
                    abort(404);
                }
            } else {
                $request->merge([
                    'deal_types' => [DealType::RentMonthly->value, DealType::RentDaily->value],
                ]);
            }

            return;
        }

        abort(404);
    }
}

