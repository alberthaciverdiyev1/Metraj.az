<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Location\Models\City;
use App\Modules\Location\Services\LocationService;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Enums\DealType;
use App\Modules\Property\Services\PropertyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected PropertyService $propertyService,
        protected LocationService $locationService,
    ) {}

    public function __invoke(Request $request, ?string $first = null, ?string $second = null, ?string $third = null)
    {
        // AJAX istekleri her zaman canlı veri döndürür (keşlenmez)
        if ($request->ajax() || $request->wantsJson()) {
            return $this->ajaxListing($request, $first, $second, $third);
        }

        // Tam səhifə keşi: qonaq ziyarətçilər üçün qısa TTL.
        // Giriş etmiş istifadəçilər her zaman canlı render edilir (öz navbar məlumatları/CSRF üçün).
        // Flash mesajlar varsa keşlənir ki, istifadəçiyə göstərilsin.
        if (auth()->guest()
            && ! session()->has('success')
            && ! session()->has('error')
            && ! $request->has('_cache_bust')) {
            $cacheKey = 'listing_page:'.md5($request->fullUrl().'|'.session('currency').'|'.app()->getLocale());

            $html = \Illuminate\Support\Facades\Cache::remember(
                $cacheKey,
                60,
                fn () => $this->renderListingPage($request, $first, $second, $third)
            );

            return response($html);
        }

        return $this->renderListingPage($request, $first, $second, $third);
    }

    /**
     * AJAX (filtr/paginator) sorğuları — canlı məlumat qaytarır.
     */
    protected function ajaxListing(Request $request, ?string $first, ?string $second, ?string $third)
    {
        if ($first !== null) {
            $this->applyPathFilters($request, $first, $second, $third);
        }

        $filter = PropertyFilterDTO::fromArray($request->all());
        $properties = $this->propertyService->paginate($filter, 30);

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
        if ($first !== null) {
            $this->applyPathFilters($request, $first, $second, $third);
        }

        $filter = PropertyFilterDTO::fromArray($request->all());
        $properties = $this->propertyService->paginate($filter, 30);

        $cities = $this->locationService->activeCities();

        // Əsas axtarışda göstərilən əmlak növü seçimləri
        $buildingTypes = $this->locationService->propertyTypeOptions();

        // Əlavə filtr pəncərəsində göstərilən dinamik filtrlər
        // (əsas axtarışda olan deal_type və property_type istisna olunur)
        $dynamicFilters = $this->locationService->dynamicFilters();

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Property Listing')],
        ];

        return view('pages.property.list', compact(
            'properties',
            'cities',
            'buildingTypes',
            'dynamicFilters',
            'breadcrumbs'
        ))->with('css', ['listing.css'])->render();
    }

    /**
     * SEO dostu URL segmentlərini filtrə çevirir.
     *
     * Mümkün URL formaları:
     *   /girne                → şəhər
     *   /satilik              → satış
     *   /kira                 → bütün kirayə (aylıq + günlük)
     *   /kira/ayliq           → aylıq kirayə
     *   /kira/gunluk          → günlük kirayə
     *   /girne/satilik        → şəhər + satış
     *   /girne/kira/gunluk    → şəhər + günlük kirayə
     */
    protected function applyPathFilters(Request $request, string $first, ?string $second, ?string $third): void
    {
        $city = City::where('slug', $first)->where('is_active', true)->first();

        if ($city) {
            $request->merge(['cityId' => $city->id]);

            if ($second !== null) {
                $this->applyDealSegment($request, $second, $third);
            } elseif ($third !== null) {
                abort(404);
            }

            return;
        }

        $this->applyDealSegment($request, $first, $second);
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
