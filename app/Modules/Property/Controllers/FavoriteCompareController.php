<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\Compare;
use App\Modules\Property\Models\Favorite;
use App\Modules\Property\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteCompareController extends Controller
{
    /**
     * Sevimlilərə əlavə et / çıxar (Backend Toggle)
     */
    public function toggleFavorite(Request $request): JsonResponse
    {
        $propertyId = (int) $request->input('property_id');
        if (! $propertyId || ! Property::where('id', $propertyId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Property not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::where('property_id', $propertyId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $isFavorite = false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'property_id' => $propertyId,
            ]);
            $isFavorite = true;
        }

        $countQuery = Favorite::query();
        if ($userId) {
            $countQuery->where('user_id', $userId);
        } else {
            $countQuery->where('session_id', $sessionId);
        }

        $ids = (clone $countQuery)->pluck('property_id')->toArray();
        $count = count($ids);

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
            'count' => $count,
            'ids' => $ids,
        ]);
    }

    /**
     * Cari istifadəçinin bütün favorit ID-ləri
     */
    public function getFavorites(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $ids = $query->pluck('property_id')->toArray();

        return response()->json([
            'success' => true,
            'count' => count($ids),
            'ids' => $ids,
        ]);
    }

    /**
     * Bütün favoritləri təmizlə
     */
    public function clearFavorites(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return response()->json([
            'success' => true,
            'count' => 0,
            'ids' => [],
        ]);
    }

    /**
     * Müqayisəyə əlavə et / çıxar (Backend Toggle)
     */
    public function toggleCompare(Request $request): JsonResponse
    {
        $propertyId = (int) $request->input('property_id');
        if (! $propertyId || ! Property::where('id', $propertyId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Property not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::where('property_id', $propertyId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $isCompared = false;
        } else {
            $countQuery = Compare::query();
            if ($userId) {
                $countQuery->where('user_id', $userId);
            } else {
                $countQuery->where('session_id', $sessionId);
            }

            if ($countQuery->count() >= 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ən çox 4 elan müqayisə edilə bilər.',
                    'limit_reached' => true,
                ], 422);
            }

            Compare::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'property_id' => $propertyId,
            ]);
            $isCompared = true;
        }

        $countQuery = Compare::query();
        if ($userId) {
            $countQuery->where('user_id', $userId);
        } else {
            $countQuery->where('session_id', $sessionId);
        }

        $ids = (clone $countQuery)->pluck('property_id')->toArray();
        $count = count($ids);

        return response()->json([
            'success' => true,
            'is_compared' => $isCompared,
            'count' => $count,
            'ids' => $ids,
        ]);
    }

    /**
     * Cari istifadəçinin bütün müqayisə ID-ləri
     */
    public function getCompares(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $ids = $query->pluck('property_id')->toArray();

        return response()->json([
            'success' => true,
            'count' => count($ids),
            'ids' => $ids,
        ]);
    }

    /**
     * Bütün müqayisə siyahısını təmizlə
     */
    public function clearCompares(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return response()->json([
            'success' => true,
            'count' => 0,
            'ids' => [],
        ]);
    }
}
