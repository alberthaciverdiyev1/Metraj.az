<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\ListingPhoneReveal;
use App\Modules\Property\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RevealPhoneController extends Controller
{
    /**
     * Reveal phone number for a property and log the interaction.
     */
    public function reveal(Request $request, string|int $listing): JsonResponse
    {
        /** @var Property $property */
        $property = Property::query()
            ->where('id', $listing)
            ->orWhere('slug', $listing)
            ->first();

        if (! $property) {
            return response()->json([
                'success' => false,
                'message' => __('property.not_found') ?: 'İlan tapılmadı.',
            ], 404);
        }

        // Determine phone
        $phone = $property->agent?->phone
            ?? $property->agency?->phone
            ?? $property->phone
            ?? $property->user?->phone
            ?? null;

        // Determine whatsapp
        $whatsapp = $property->agent?->whatsapp
            ?? $property->agency?->whatsapp
            ?? $property->whatsapp
            ?? $property->phone
            ?? $property->user?->phone
            ?? null;

        $cleanPhone = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
        $cleanWhatsapp = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : null;

        // Track reveal in listing_phone_reveals table
        try {
            ListingPhoneReveal::create([
                'listing_id' => $property->id,
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'phone' => $phone,
            'clean_phone' => $cleanPhone,
            'whatsapp' => $whatsapp,
            'clean_whatsapp' => $cleanWhatsapp,
            'whatsapp_url' => $cleanWhatsapp ? 'https://wa.me/' . $cleanWhatsapp : null,
            'call_url' => $cleanPhone ? 'tel:' . $cleanPhone : null,
        ]);
    }
}
