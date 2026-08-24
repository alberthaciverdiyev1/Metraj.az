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
        $filter = PropertyFilterDTO::fromArray($request->all());
        $properties = $this->propertyService->paginate($filter, 30);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'properties' => view('pages.property.partials.cards', compact('properties'))->render(),
                'pagination' => view('pages.property.partials.pagination', compact('properties'))->render(),
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

        return view('pages.property.list', compact(
            'properties',
            'cities',
            'buildingTypes',
            'dynamicFilters',
            'breadcrumbs'
        ))->with('css', ['listing.css']);
    }
}
