<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Haqqımızda'), 'url' => null],
        ];

        return view('pages.static.about-us', compact('breadcrumbs'));
    }

    public function contact(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Əlaqə'), 'url' => null],
        ];

        return view('pages.static.contact', compact('breadcrumbs'));
    }

    public function faq(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('FAQ'), 'url' => null],
        ];

        return view('pages.static.faq', compact('breadcrumbs'));
    }

    public function favorites(\Illuminate\Http\Request $request): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Seçilmişlər'), 'url' => null],
        ];

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $favoritePropertyIds = \App\Modules\Property\Models\Favorite::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)
            ->pluck('property_id')
            ->toArray();

        $properties = \App\Modules\Property\Models\Property::whereIn('id', $favoritePropertyIds)
            ->where('status', \App\Modules\Property\Enums\PropertyStatus::Published)
            ->with(['images', 'filterOptions.filter', 'city', 'district'])
            ->get()
            ->sortBy(function ($property) use ($favoritePropertyIds) {
                return array_search($property->id, $favoritePropertyIds);
            });

        return view('pages.favorites.favorites', compact('breadcrumbs', 'properties'));
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
            ->with(['images', 'filterOptions.filter', 'city', 'district'])
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

    public function compares(\Illuminate\Http\Request $request): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Müqayisə'), 'url' => null],
        ];

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $comparePropertyIds = \App\Modules\Property\Models\Compare::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)
            ->pluck('property_id')
            ->toArray();

        $properties = \App\Modules\Property\Models\Property::whereIn('id', $comparePropertyIds)
            ->where('status', \App\Modules\Property\Enums\PropertyStatus::Published)
            ->with(['images', 'filterOptions.filter', 'city', 'district', 'amenities'])
            ->get()
            ->sortBy(function ($property) use ($comparePropertyIds) {
                return array_search($property->id, $comparePropertyIds);
            });

        return view('pages.compare.compare', compact('breadcrumbs', 'properties'));
    }

    public function login(): View
    {
        return view('pages.auth.login');
    }
}
