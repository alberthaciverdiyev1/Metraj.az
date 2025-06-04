<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class BaseController extends Controller
{
    public function rooms()
    {

        return get_data('room-count', [], true);
    }

    public function property_types()
    {

        return get_data('property-types', [], true);

    }

    public function cities()
    {
        $response = Http::get(config('app.api_url') . '/api/city')->json();

        return $response['data'] ?? [];
    }

    public function features()
    {
        return get_data('feature');
    }

}
