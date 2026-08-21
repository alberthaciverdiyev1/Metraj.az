<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Property\Services\PropertyService;
use App\Modules\Property\Models\Property;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PropertyDetailController extends Controller
{
    public function __construct(
        protected PropertyService $propertyService,
    ) {}

    public function __invoke(string $slug): View
    {
        $property = $this->propertyService->findPublishedBySlug($slug);

        abort_unless($property instanceof Property, 404);

        // Baxış sayını artırırıq
        $this->propertyService->incrementViews($property->id);

        // Oxşar elanlar: eyni əmlak növü və ya eyni şəhər/rayonda olan digər dərc edilmiş elanlar
        $similarProperties = $this->propertyService->similar($property, 3);

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
