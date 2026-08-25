<?php

namespace App\Modules\Property\Controllers;

use App\Modules\Property\Services\PropertyService;
use App\Modules\Property\Models\Property;
use App\Modules\Shared\Concerns\CachesGuestPage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PropertyDetailController extends Controller
{
    use CachesGuestPage;

    public function __construct(
        protected PropertyService $propertyService,
    ) {}

    public function __invoke(string $slug): \Illuminate\Http\Response|View
    {
        if (auth()->guest() && ! request()->has('_cache_bust')) {
            $key = 'page_cache:property_details:'.md5(
                $slug.'|'.session('currency').'|'.app()->getLocale()
            );

            $html = Cache::remember($key, 60, fn () => $this->renderDetails($slug));

            // Keşlənmiş səhifədəki CSRF tokenini cari sessiyanın tokeni ilə yenilə
            // (fərqli qonaq sessiyaları üçün form göndərimi 419 verməsin).
            $html = $this->refreshCsrfToken($html);

            return response($html);
        }

        return response($this->renderDetails($slug));
    }

    protected function renderDetails(string $slug): string
    {
        $property = $this->propertyService->findPublishedBySlug($slug);

        abort_unless($property instanceof Property, 404);

        // Baxış sayını yalnızca render (keş miss) zamanı artır.
        // Keş hit-lərində təkrar UPDATE (PostgreSQL ~15-20ms fsync) olmaz → sürət qazanır.
        Property::where('slug', $slug)->increment('views_count');

        // Oxşar elanlar: eyni əmlak növü və ya eyni şəhər/rayonda olan digər dərc edilmiş elanlar
        $similarProperties = $this->propertyService->similar($property, 3);

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('property.property_details')],
        ];

        return view('pages.property.details', compact('property', 'similarProperties', 'breadcrumbs'))
            ->render();
    }

}
