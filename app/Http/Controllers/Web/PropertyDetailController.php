<?php

namespace App\Http\Controllers\Web;

use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PropertyDetailController extends Controller
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
    ) {}

    public function __invoke(string $slug): View
    {
        $property = Property::with([
            'images',
            'city',
            'district',
            'agency',
            'agent.user',
            'amenities',
            'filterOptions.filter',
            'filterOptions.parent'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        // Baxış sayını artırırıq
        $this->propertyRepository->incrementViews($property->id);

        // Oxşar elanlar: eyni əmlak növü və ya eyni şəhər/rayonda olan digər dərc edilmiş elanlar
        $propertyTypeOpt = $property->filterOptions->first(fn ($opt) => $opt->filter?->key === \App\Core\Domain\Filter\Enums\FilterKey::PropertyType);
        $similarQuery = Property::with([
            'images',
            'city',
            'district',
            'agency',
            'agent.user',
            'filterOptions.filter'
        ])
        ->where('id', '!=', $property->id)
        ->where('status', 'published');

        if ($propertyTypeOpt) {
            $similarQuery->whereHas('filterOptions', function ($q) use ($propertyTypeOpt) {
                $q->where('filter_options.id', $propertyTypeOpt->id);
            });
        } elseif ($property->city_id) {
            $similarQuery->where('city_id', $property->city_id);
        }

        $similarProperties = $similarQuery->latest('id')->limit(3)->get();

        // Əgər 3-dən azdırsa, digər sonuncu dərc edilmiş elanlarla tamamlayırıq
        if ($similarProperties->count() < 3) {
            $excludeIds = $similarProperties->pluck('id')->push($property->id)->toArray();
            $fillCount = 3 - $similarProperties->count();
            $moreProperties = Property::with([
                'images',
                'city',
                'district',
                'agency',
                'agent.user',
                'filterOptions.filter'
            ])
            ->whereNotIn('id', $excludeIds)
            ->where('status', 'published')
            ->latest('id')
            ->limit($fillCount)
            ->get();

            $similarProperties = $similarProperties->concat($moreProperties);
        }

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Property Details')],
        ];

        return view('pages.property.details', compact('property', 'similarProperties', 'breadcrumbs'))
            ->with('css', ['listing-details.css'])
            ->with('js', [
                '/js/pages/property/detail/phone-modal.js',
                '/js/pages/property/detail/videoplay.js',
                '/js/pages/property/detail/image-gallery.js',
                '/js/pages/property/detail/modal.js',
            ]);
    }
}
