<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Location\Services\LocationService;
use App\Modules\Property\DTOs\PropertyFilterDTO;
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

    public function __invoke(Request $request)
    {
        $params = $request->all();
        
        // Translate front-end parameters to DTO parameters
        if ($request->has('roomCount') && $request->get('roomCount') !== '') {
            $params['rooms'] = $request->get('roomCount');
        }
        if ($request->has('adType') && $request->get('adType') !== '' && $request->get('adType') !== 'all') {
            $adType = $request->get('adType');
            if ($adType === 'sale') {
                $params['deal_type'] = 'sale';
            } elseif ($adType === 'rent') {
                $params['deal_type'] = 'rent_monthly';
            } else {
                $params['deal_type'] = $adType;
            }
        }
        if ($request->has('buildingType') && $request->get('buildingType') !== '') {
            $params['property_type'] = $request->get('buildingType');
        }
        if ($request->has('cityId') && $request->get('cityId') !== '') {
            $params['city_id'] = $request->get('cityId');
        }
        if ($request->has('minPrice') && $request->get('minPrice') !== '') {
            $params['min_price'] = $request->get('minPrice');
        }
        if ($request->has('maxPrice') && $request->get('maxPrice') !== '') {
            $params['max_price'] = $request->get('maxPrice');
        }
        if ($request->has('minArea') && $request->get('minArea') !== '') {
            $params['min_area'] = $request->get('minArea');
        }
        if ($request->has('maxArea') && $request->get('maxArea') !== '') {
            $params['max_area'] = $request->get('maxArea');
        }
        if ($request->has('advertiserType') && $request->get('advertiserType') !== '') {
            $adv = $request->get('advertiserType');
            if ($adv === 'user' || $adv === 'owner') {
                $params['seller_type'] = 'owner';
            } elseif ($adv === 'realtor' || $adv === 'agency') {
                $params['seller_type'] = 'agency';
            } elseif ($adv === 'complex') {
                $params['seller_type'] = 'complex';
            }
        }

        $filter = PropertyFilterDTO::fromArray($params);
        $properties = $this->propertyService->paginate($filter, 12);

        if ($request->ajax() || $request->wantsJson()) {
            $premiumProperties = $this->propertyService->filterPremium($properties);

            return response()->json([
                'premium' => view('property::pages.property.partials.premium', compact('premiumProperties'))->render(),
                'properties' => view('property::pages.property.partials.cards', compact('properties'))->render(),
                'pagination' => view('property::pages.property.partials.pagination', compact('properties'))->render(),
                'total' => $properties->total(),
            ]);
        }

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

        return view('property::pages.property.list', compact(
            'properties',
            'cities',
            'buildingTypes',
            'dynamicFilters',
            'breadcrumbs'
        ))->with('css', ['listing.css']);
    }
}
