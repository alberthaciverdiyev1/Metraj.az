<?php

namespace Modules\WebUI\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class API
{
    public static function call(string $url, string $method = 'GET')
    {

        $request = Request::create($url, $method);
        $response = Route::dispatch($request);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getContent(), false);
            return $data->data ?? new \stdClass();
        }

        return new \stdClass();
    }
}
