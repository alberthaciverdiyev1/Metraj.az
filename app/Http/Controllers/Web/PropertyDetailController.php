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

        // Oxşar elanlar
        $similarProperties = Property::with(['agency', 'agent', 'filterOptions.filter'])
            ->where('id', '!=', $property->id)
            ->where('status', 'published')
            ->latest('id')
            ->limit(3)
            ->get();

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
