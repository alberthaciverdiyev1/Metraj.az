<?php

namespace App\Http\Controllers\Web;

use App\Core\Application\Property\DTOs\PropertyFilterDTO;
use App\Core\Application\Property\UseCases\SearchPropertiesUseCase;
use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Filter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected SearchPropertiesUseCase $searchPropertiesUseCase,
        protected PropertyRepositoryInterface $propertyRepository,
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

            $premiumHtml = '';
            if ($premiumProperties->isNotEmpty()) {
                foreach ($premiumProperties as $property) {
                    $premiumHtml .= view('components.property-card', ['property' => $property])->render();
                }
            } else {
                $premiumHtml = '<p class="col-span-full text-center text-gray-500">' . __('Axtarışınıza uyğun premium elan tapılmadı.') . '</p>';
            }

            $propertiesHtml = '';
            if ($properties->count() > 0) {
                foreach ($properties as $property) {
                    $propertiesHtml .= view('components.property-card', ['property' => $property])->render();
                }
            } else {
                $propertiesHtml = '<p class="col-span-full text-center text-gray-500 py-10">' . __('Elan tapılmadı.') . '</p>';
            }

            $paginationHtml = $properties->onEachSide(2)->appends($request->except('json'))->links('pagination.metraj')->render();

            return response()->json([
                'premium' => $premiumHtml,
                'properties' => $propertiesHtml,
                'pagination' => $paginationHtml,
                'total' => $properties->total(),
            ]);
        }
        
        $cities = \App\Core\Infrastructure\Persistence\Eloquent\Models\City::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $buildingTypes = \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', 3)
            ->get();

        $dynamicFilters = \App\Core\Infrastructure\Persistence\Eloquent\Models\Filter::with('options')
            ->where('is_active', true)
            ->whereNotIn('id', [1, 2, 3])
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
