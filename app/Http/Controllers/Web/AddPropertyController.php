<?php

namespace App\Http\Controllers\Web;

use App\Core\Application\Currency\CurrencyService;
use App\Core\Application\Property\DTOs\CreatePropertyDTO;
use App\Core\Application\Property\Services\PropertyTitleBuilder;
use App\Core\Application\Property\UseCases\CreatePropertyUseCase;
use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Domain\Property\Enums\SellerType;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Amenity;
use App\Core\Infrastructure\Persistence\Eloquent\Models\City;
use App\Core\Infrastructure\Persistence\Eloquent\Models\District;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Filter;
use App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Core\Infrastructure\Persistence\Eloquent\Models\PropertyImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AddPropertyController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService,
        protected CreatePropertyUseCase $createPropertyUseCase,
    ) {}

    public function create(): View
    {
        $cities = City::with('activeDistricts')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $filters = Filter::with('options')->get()->keyBy(fn ($f) => $f->key->value ?? (string) $f->key);

        $dealTypes = $filters['deal_type']?->options ?? collect();
        $propertyTypes = $filters['property_type']?->options ?? collect();
        $buildingTypes = $filters['building_type']?->options ?? collect();
        $repairTypes = $filters['repair_type']?->options ?? collect();
        $heatingSystems = $filters['heating_system']?->options ?? collect();
        $windowViews = $filters['window_view']?->options ?? collect();
        $amenities = Amenity::orderBy('name')->get();
        $dailyRates = $this->currencyService->getRatesFromGbp();

        return view('pages.property.add', compact(
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_type_id' => 'required|exists:filter_options,id',
            'deal_type_id' => 'required|exists:filter_options,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_gbp' => 'required|numeric|min:1',
            'prices' => 'nullable|array',
            'area' => 'nullable|numeric|min:1',
            'land_area' => 'nullable|numeric|min:0.1',
            'rooms' => 'nullable|integer|min:1',
            'floor' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'building_type_id' => 'nullable|exists:filter_options,id',
            'repair_type_id' => 'nullable|exists:filter_options,id',
            'heating_system_id' => 'nullable|exists:filter_options,id',
            'window_view_id' => 'nullable|exists:filter_options,id',
            'description' => 'nullable|string',
            'has_document' => 'nullable|boolean',
            'has_mortgage' => 'nullable|boolean',
            'has_internal_credit' => 'nullable|boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'advertiser' => 'required|in:owner,agent',
            'advertiser_name' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $baseGbp = (float) $validated['price_gbp'];
            $prices = $request->input('prices', []);

            // Auto-calculate rates if missing
            if (empty($prices) || count($prices) < 2) {
                $prices = $this->currencyService->convertFromGbp($baseGbp);
            }
            $prices['GBP'] = $baseGbp;

            // Unique kod Property modelinin generateUniqueCode() metodu ilə alınır;
            // bu kod həm slug üçün, həm də create zamanı istifadə olunur.
            $code = Property::generateUniqueCode();

            // Detect if property is land
            $propTypeOption = FilterOption::find($validated['property_type_id']);
            $isLand = $propTypeOption && str_contains(mb_strtolower($propTypeOption->name['az'] ?? $propTypeOption->value), 'torpaq');

            // Build dynamic title (tək qaynaq: PropertyTitleBuilder servisi)
            $city = City::find($validated['city_id']);
            $district = !empty($validated['district_id']) ? District::find($validated['district_id']) : null;

            $locationLabel = $district ? ($district->name['az'] ?? $district->name['tr'] ?? '')
                : ($city ? ($city->name['az'] ?? $city->name['tr'] ?? '') : '');

            $filterOptionIds = array_filter([
                $validated['property_type_id'] ?? null,
                $validated['deal_type_id'] ?? null,
                $isLand ? null : ($validated['building_type_id'] ?? null),
                $isLand ? null : ($validated['repair_type_id'] ?? null),
                $isLand ? null : ($validated['heating_system_id'] ?? null),
                $isLand ? null : ($validated['window_view_id'] ?? null),
            ]);

            $generatedTitle = app(PropertyTitleBuilder::class)
                ->build(
                    array_values($filterOptionIds),
                    $isLand ? null : ($validated['rooms'] ?? null),
                    $isLand ? null : ($validated['area'] ?? null),
                    $validated['land_area'] ?? null,
                    $locationLabel
                );
            $baseSlug = Str::slug($generatedTitle);
            $slug = $baseSlug . '-' . $code;

            $sellerType = ($validated['advertiser'] === 'agent') ? SellerType::Agent : SellerType::Owner;

            // Tək qaynaq: CreatePropertyUseCase + CreatePropertyDTO
            // Repo property, filterOptions və amenities sync-lərini özü idarə edir.
            $property = $this->createPropertyUseCase->execute(new CreatePropertyDTO(
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

            // Handle Images Upload
            if ($request->hasFile('photos')) {
                $order = 0;
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('properties', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'url' => $path,
                        'sort_order' => $order++,
                    ]);
                }
            }

            return redirect()->route('home')->with('success', 'Elanınız uğurla qəbul edildi! Qısa müddətdə moderator tərəfindən yoxlanıldıqdan sonra saytda dərc olunacaq.');
        });
    }
}
