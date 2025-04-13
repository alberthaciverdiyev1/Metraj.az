<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class CallAPI
{
    /**
     *
     *
     * @param string $url
     * @param string $method
     * @return array|null
     */
    public function __invoke(string $url, string $method = 'GET')
    {
        $request = Request::create($url, $method);

        $response = Route::dispatch($request);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getContent(), true);
        }

        return [];
    }

}
