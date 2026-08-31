<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public static function favoritesCacheKey(?int $userId, ?string $sessionId): string
    {
        return 'favorites_page:'.($userId ?: $sessionId);
    }

    public static function comparesCacheKey(?int $userId, ?string $sessionId): string
    {
        return 'compares_page:'.($userId ?: $sessionId);
    }

    public function favorites(\Illuminate\Http\Request $request): \Illuminate\Http\Response|View
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $html = Cache::remember(self::favoritesCacheKey($userId, $sessionId), 60, function () use ($request, $userId, $sessionId) {
            $breadcrumbs = [
                ['label' => __('navbar.home'), 'url' => '/'],
                ['label' => __('favorites.page_title'), 'url' => null],
            ];

            $favoritePropertyIds = \App\Modules\Property\Models\Favorite::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)
                ->pluck('property_id')
                ->toArray();

            $properties = \App\Modules\Property\Models\Property::whereIn('id', $favoritePropertyIds)
                ->where('status', \App\Modules\Property\Enums\PropertyStatus::Published)
                ->with(['images', 'filterOptions', 'city', 'district'])
                ->get()
                ->sortBy(function ($property) use ($favoritePropertyIds) {
                    return array_search($property->id, $favoritePropertyIds);
                });

            return view('pages.favorites.favorites', compact('breadcrumbs', 'properties'))->render();
        });

        return response($html);
    }

    /**
     * Favorit elanların dinamik AJAX ilə yüklənməsi.
     */
    public function favoritesItems(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $ids = array_filter(array_map('intval', (array) $ids));

        if (empty($ids)) {
            $userId = auth()->id();
            $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';
            $ids = \App\Modules\Property\Models\Favorite::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)
                ->pluck('property_id')
                ->toArray();
        }

        if (empty($ids)) {
            return response()->json([
                'success' => true,
                'count' => 0,
                'html' => '',
            ]);
        }

        $properties = \App\Modules\Property\Models\Property::whereIn('id', $ids)
            ->where('status', \App\Modules\Property\Enums\PropertyStatus::Published)
            ->with(['images', 'filterOptions', 'city', 'district'])
            ->get()
            ->sortBy(function ($property) use ($ids) {
                return array_search($property->id, $ids);
            });

        $html = view('pages.favorites.partials.cards', compact('properties'))->render();

        return response()->json([
            'success' => true,
            'count' => $properties->count(),
            'html' => $html,
        ]);
    }

    public function compares(\Illuminate\Http\Request $request): \Illuminate\Http\Response|View
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $html = Cache::remember(self::comparesCacheKey($userId, $sessionId), 60, function () use ($userId, $sessionId) {
            $breadcrumbs = [
                ['label' => __('navbar.home'), 'url' => '/'],
                ['label' => __('compare.page_title'), 'url' => null],
            ];

            $comparePropertyIds = \App\Modules\Property\Models\Compare::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)
                ->pluck('property_id')
                ->toArray();

            $properties = \App\Modules\Property\Models\Property::whereIn('id', $comparePropertyIds)
                ->where('status', \App\Modules\Property\Enums\PropertyStatus::Published)
                ->with(['images', 'filterOptions', 'city', 'district', 'amenities'])
                ->get()
                ->sortBy(function ($property) use ($comparePropertyIds) {
                    return array_search($property->id, $comparePropertyIds);
                });

            return view('pages.compare.compare', compact('breadcrumbs', 'properties'))->render();
        });

        return response($html);
    }

    public function about(): View
    {
        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('about.page_title'), 'url' => null],
        ];

        return view('pages.static.about-us', compact('breadcrumbs'));
    }

    public function contact(): View
    {
        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('contact.page_title'), 'url' => null],
        ];

        return view('pages.static.contact', compact('breadcrumbs'));
    }

    public function faq(): View
    {
        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => __('faq.page_title'), 'url' => null],
        ];

        $faqs = \App\Modules\Shared\Models\Faq::active()->get();

        return view('pages.static.faq', compact('breadcrumbs', 'faqs'));
    }

    public function userAgreement(): View
    {
        $siteSetting = \App\Modules\Shared\Models\SiteSetting::current();
        $title = __('footer.user_agreement');
        $activeDoc = 'user_agreement';
        $content = $siteSetting?->getTrans('user_agreement') ?: '';

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => $title, 'url' => null],
        ];

        return view('pages.static.legal', compact('breadcrumbs', 'title', 'activeDoc', 'content', 'siteSetting'));
    }

    public function privacyPolicy(): View
    {
        $siteSetting = \App\Modules\Shared\Models\SiteSetting::current();
        $title = __('footer.privacy_policy');
        $activeDoc = 'privacy_policy';
        $content = $siteSetting?->getTrans('privacy_policy') ?: '';

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => $title, 'url' => null],
        ];

        return view('pages.static.legal', compact('breadcrumbs', 'title', 'activeDoc', 'content', 'siteSetting'));
    }

    public function termsOfUse(): View
    {
        $siteSetting = \App\Modules\Shared\Models\SiteSetting::current();
        $title = __('footer.terms_of_use') ?: 'Kullanım Koşulları';
        $activeDoc = 'terms_of_use';
        $content = $siteSetting?->getTrans('terms_of_use') ?: '';

        $breadcrumbs = [
            ['label' => __('navbar.home'), 'url' => '/'],
            ['label' => $title, 'url' => null],
        ];

        return view('pages.static.legal', compact('breadcrumbs', 'title', 'activeDoc', 'content', 'siteSetting'));
    }
}
