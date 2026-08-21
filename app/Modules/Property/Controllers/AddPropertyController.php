<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Shared\Services\CurrencyService;
use App\Modules\Location\Services\LocationService;
use App\Modules\Property\DTOs\CreatePropertyDTO;
use App\Modules\Property\Services\PropertyService;
use App\Modules\Property\Services\PropertyTitleBuilder;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Enums\SellerType;
use App\Http\Controllers\Controller;
use App\Modules\Property\Requests\StorePropertyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AddPropertyController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService,
        protected PropertyService $propertyService,
        protected LocationService $locationService,
        protected PropertyTitleBuilder $titleBuilder,
    ) {}

    public function create(): View
    {
        $cities = $this->locationService->activeCities();

        $filters = $this->locationService->allFiltersKeyed();

        $dealTypes = $filters['deal_type']?->options ?? collect();
        $propertyTypes = $filters['property_type']?->options ?? collect();
        $buildingTypes = $filters['building_type']?->options ?? collect();
        $repairTypes = $filters['repair_type']?->options ?? collect();
        $heatingSystems = $filters['heating_system']?->options ?? collect();
        $windowViews = $filters['window_view']?->options ?? collect();
        $amenities = $this->locationService->amenities();
        $dailyRates = $this->currencyService->getRatesFromGbp();

        return view('property::pages.property.add', compact(
            'cities',
            'dealTypes',
            'propertyTypes',
            'buildingTypes',
            'repairTypes',
            'heatingSystems',
            'windowViews',
            'amenities',
            'dailyRates'
        ));
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated) {
            $baseGbp = (float) $validated['price_gbp'];
            $prices = $request->input('prices', []);

            // Auto-calculate rates if missing
            if (empty($prices) || count($prices) < 2) {
                $prices = $this->currencyService->convertFromGbp($baseGbp);
            }
            $prices['GBP'] = $baseGbp;

            // Unikal elan kodu (həm slug üçün, həm də elan kodudur)
            $code = $this->propertyService->generateCode();

            // Elanın torpaq olub-olmadığını əmlak növündən yoxlayırıq
            $propTypeOption = $this->locationService->filterOptionById($validated['property_type_id']);
            $isLand = $propTypeOption !== null && $this->propertyService->isLandOption($propTypeOption);

            $locationLabel = $this->locationService->locationLabel(
                (int) $validated['city_id'],
                !empty($validated['district_id']) ? (int) $validated['district_id'] : null
            );

            $filterOptionIds = array_filter([
                $validated['property_type_id'] ?? null,
                $validated['deal_type_id'] ?? null,
                $isLand ? null : ($validated['building_type_id'] ?? null),
                $isLand ? null : ($validated['repair_type_id'] ?? null),
                $isLand ? null : ($validated['heating_system_id'] ?? null),
                $isLand ? null : ($validated['window_view_id'] ?? null),
            ]);

            $generatedTitle = $this->titleBuilder->build(
                array_values($filterOptionIds),
                $isLand ? null : ($validated['rooms'] ?? null),
                $isLand ? null : ($validated['area'] ?? null),
                $validated['land_area'] ?? null,
                $locationLabel
            );
            $slug = Str::slug($generatedTitle) . '-' . $code;

            $sellerType = ($validated['advertiser'] === 'agent') ? SellerType::Agent : SellerType::Owner;

            // Tək qaynaq: PropertyService::create → CreatePropertyDTO
            // Repo property, filterOptions və amenities sync-lərini özü idarə edir.
            $property = $this->propertyService->create(new CreatePropertyDTO(
                title: $generatedTitle,
                description: $validated['description'] ?? '',
                code: $code,
                slug: $slug,
                hasDocument: $request->boolean('has_document'),
                hasMortgage: $request->boolean('has_mortgage'),
                hasInternalCredit: $request->boolean('has_internal_credit'),
                price: $baseGbp,
                currency: 'GBP',
                prices: $prices,
                viewsCount: 0,
                area: $isLand ? null : (isset($validated['area']) ? (int) $validated['area'] : null),
                landArea: isset($validated['land_area']) ? (int) $validated['land_area'] : null,
                rooms: $isLand ? null : (isset($validated['rooms']) ? (int) $validated['rooms'] : null),
                floor: $isLand ? null : (isset($validated['floor']) ? (int) $validated['floor'] : null),
                totalFloors: $isLand ? null : (isset($validated['total_floors']) ? (int) $validated['total_floors'] : null),
                cityId: (int) $validated['city_id'],
                districtId: !empty($validated['district_id']) ? (int) $validated['district_id'] : null,
                address: $validated['address'],
                latitude: isset($validated['latitude']) ? (float) $validated['latitude'] : null,
                longitude: isset($validated['longitude']) ? (float) $validated['longitude'] : null,
                userId: auth()->id(),
                sellerType: $sellerType,
                status: PropertyStatus::PendingApproval,
                filterOptionIds: array_values($filterOptionIds),
                amenityIds: (!empty($validated['amenities']) && !$isLand)
                    ? array_map('intval', $validated['amenities'])
                    : [],
            ));

            // Yüklənən fotoşəkilləri əmlaka əlavə edirik
            if ($request->hasFile('photos')) {
                $this->propertyService->storeImages($property, $request->file('photos'));
            }

            return redirect()->route('home')->with('success', 'Elanınız uğurla qəbul edildi! Qısa müddətdə moderator tərəfindən yoxlanıldıqdan sonra saytda dərc olunacaq.');
        });
    }
}
