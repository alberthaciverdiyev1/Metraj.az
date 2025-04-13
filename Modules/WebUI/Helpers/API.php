<?php

namespace Modules\WebUI\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class API
{
    public static function call(string $url, string $method = 'GET', bool $is_enum = false)
    {
        $request = Request::create($url, $method);
        $response = Route::dispatch($request);


        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getContent(), false);
            if ($is_enum) return $data;

            return $data->data ?? new \stdClass();
        }

        return new \stdClass();
    }
}
