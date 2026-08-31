<?php

namespace App\Modules\Agency\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgencyRevealPhoneController extends Controller
{
    /**
     * Reveal phone number for an Agency.
     */
    public function revealAgency(Request $request, ...$args): JsonResponse
    {
        $agencyParam = $request->route('agency') ?? end($args);

        if (! $agencyParam) {
            return response()->json([
                'success' => false,
                'message' => 'Agency parameter missing.',
            ], 400);
        }

        /** @var Agency|null $agency */
        $agency = is_numeric($agencyParam)
            ? Agency::where('id', (int) $agencyParam)->first()
            : Agency::where('slug', (string) $agencyParam)->first();

        if (! $agency) {
            return response()->json([
                'success' => false,
                'message' => __('agency.no_results_title') ?: 'Agentlik tapılmadı.',
            ], 404);
        }

        $phone = $agency->phone;
        $whatsapp = $agency->whatsapp ?: $agency->phone;

        $cleanPhone = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
        $cleanWhatsapp = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : null;

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

    /**
     * Reveal phone number for an Agent.
     */
    public function revealAgent(Request $request, ...$args): JsonResponse
    {
        $agentParam = $request->route('agent') ?? ($request->route('id') ?? end($args));

        if (! $agentParam) {
            return response()->json([
                'success' => false,
                'message' => 'Agent parameter missing.',
            ], 400);
        }

        /** @var Agent|null $agent */
        $agent = is_numeric($agentParam)
            ? Agent::where('id', (int) $agentParam)->first()
            : null;

        if (! $agent) {
            return response()->json([
                'success' => false,
                'message' => __('agency.no_results_title') ?: 'Rieltor tapılmadı.',
            ], 404);
        }

        $phone = $agent->phone;
        $whatsapp = $agent->whatsapp ?: $agent->phone;

        $cleanPhone = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
        $cleanWhatsapp = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : null;

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
