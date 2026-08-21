<?php

namespace App\Http\Controllers\Web;

use App\Core\Application\Property\DTOs\PropertyFilterDTO;
use App\Core\Application\Property\UseCases\SearchPropertiesUseCase;
use App\Core\Domain\Filter\Enums\FilterKey;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Filter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected SearchPropertiesUseCase $searchPropertiesUseCase,
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
        $properties = $this->searchPropertiesUseCase->execute($filter, 12);

        if ($request->ajax() || $request->wantsJson()) {
            $premiumProperties = $properties->filter(fn($p) => $p->is_vip || $p->is_featured);

            return response()->json([
                'premium' => view('pages.property.partials.premium', compact('premiumProperties'))->render(),
                'properties' => view('pages.property.partials.cards', compact('properties'))->render(),
                'pagination' => view('pages.property.partials.pagination', compact('properties'))->render(),
                'total' => $properties->total(),
            ]);
        }
        
        $cities = \App\Core\Infrastructure\Persistence\Eloquent\Models\City::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Əsas axtarışda göstərilən əmlak növü seçimləri
        $propertyTypeFilterId = \App\Core\Infrastructure\Persistence\Eloquent\Models\Filter::where('key', FilterKey::PropertyType->value)->value('id');
        $buildingTypes = $propertyTypeFilterId
            ? \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', $propertyTypeFilterId)->get()
            : collect();

        // Əlavə filtr pəncərəsində göstərilən dinamik filtrlər
        // (əsas axtarışda olan deal_type və property_type istisna olunur)
        $dynamicFilters = \App\Core\Infrastructure\Persistence\Eloquent\Models\Filter::with('options')
            ->where('is_active', true)
            ->whereNotIn('key', [
                FilterKey::DealType->value,
                FilterKey::PropertyType->value,
            ])
            ->get();

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
        ))->with('css', ['listing.css']);
    }
}
