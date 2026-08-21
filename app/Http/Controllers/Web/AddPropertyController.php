<?php

namespace App\Http\Controllers\Web;

use App\Core\Application\Currency\CurrencyService;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AddPropertyController extends Controller
{
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
        $dailyRates = CurrencyService::getRatesFromGbp();

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
                $prices = CurrencyService::convertFromGbp($baseGbp);
            }
            $prices['GBP'] = $baseGbp;

            // Generate unique code
            $code = (string) mt_rand(100000, 999999);
            while (Property::where('code', $code)->exists()) {
                $code = (string) mt_rand(100000, 999999);
            }

            // Detect if property is land
            $propTypeOption = FilterOption::find($validated['property_type_id']);
            $isLand = $propTypeOption && str_contains(mb_strtolower($propTypeOption->name['az'] ?? $propTypeOption->value), 'torpaq');

            // Build dynamic title
            $city = City::find($validated['city_id']);
            $locationLabel = $district ? ($district->name['az'] ?? $district->name['tr'] ?? 'Girne') : ($city ? ($city->name['az'] ?? $city->name['tr'] ?? 'Girne') : 'Girne');

            $titleParts = [];
            $titleParts[] = $locationLabel;
            if ($isLand && !empty($validated['land_area'])) {
                $titleParts[] = $validated['land_area'] . ' sot';
            } elseif (!empty($validated['rooms'])) {
                $titleParts[] = $validated['rooms'] . ' otaqlı';
            }
            $titleParts[] = $typeName;
            if (!$isLand && !empty($validated['area'])) {
                $titleParts[] = $validated['area'] . ' m²';
            }

            $dealTypeOpt = FilterOption::find($validated['deal_type_id']);
            $dealName = $dealTypeOpt ? ($dealTypeOpt->name['az'] ?? 'satılır') : 'satılır';
            $titleParts[] = $dealName;

            $generatedTitle = implode(' ', array_filter($titleParts));
            $baseSlug = Str::slug($generatedTitle);
            $slug = $baseSlug . '-' . $code;

            $sellerType = ($validated['advertiser'] === 'agent') ? SellerType::Agent->value : SellerType::Owner->value;

            // Create Property
            $property = Property::create([
                'user_id' => auth()->id() ?? null,
                'code' => $code,
                'slug' => $slug,
                'title' => $generatedTitle,
                'description' => $validated['description'] ?? null,
                'price' => $baseGbp,
                'currency' => 'GBP',
                'prices' => $prices,
                'area' => $isLand ? null : ($validated['area'] ?? null),
                'land_area' => $validated['land_area'] ?? null,
                'rooms' => $isLand ? null : ($validated['rooms'] ?? null),
                'floor' => $isLand ? null : ($validated['floor'] ?? null),
                'total_floors' => $isLand ? null : ($validated['total_floors'] ?? null),
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'] ?? null,
                'address' => $validated['address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'has_document' => $request->boolean('has_document'),
                'has_mortgage' => $request->boolean('has_mortgage'),
                'has_internal_credit' => $request->boolean('has_internal_credit'),
                'seller_type' => $sellerType,
                'status' => PropertyStatus::PendingApproval->value,
                'views_count' => 0,
            ]);

            // Sync Amenities
            if (!empty($validated['amenities']) && !$isLand) {
                $property->amenities()->sync($validated['amenities']);
            }

            // Sync Filter Options
            $filterOptionIds = array_filter([
                $validated['property_type_id'] ?? null,
                $validated['deal_type_id'] ?? null,
                $isLand ? null : ($validated['building_type_id'] ?? null),
                $isLand ? null : ($validated['repair_type_id'] ?? null),
                $isLand ? null : ($validated['heating_system_id'] ?? null),
                $isLand ? null : ($validated['window_view_id'] ?? null),
            ]);
            $property->filterOptions()->sync($filterOptionIds);

            // Handle Images Upload
            if ($request->hasFile('photos')) {
                $order = 0;
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('properties', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'url' => $path,
                        'sort_order' => $order++,
                        'is_main' => ($order === 1),
                    ]);
                }
            }

            return redirect()->route('home')->with('success', 'Elanınız uğurla qəbul edildi! Qısa müddətdə moderator tərəfindən yoxlanıldıqdan sonra saytda dərc olunacaq.');
        });
    }
}
