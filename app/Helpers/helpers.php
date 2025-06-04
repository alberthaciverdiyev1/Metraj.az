<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('get_data')) {
    if (!function_exists('get_data')) {
        function get_data(string $url, array $params = [],bool $enum = false): array
        {
            $defaultParams = [
                'page'  => 1,
            ];

            $queryParams = array_merge($defaultParams, $params);

            $fullUrl = rtrim(config('app.api_url'), '/') . '/api/' . ltrim($url, '/');

            $response = Http::get($fullUrl, $queryParams);

            return $enum ? $response->json(): ($response->json('data') ?? []);
        }
    }
}
if (!function_exists('set_data')) {
    function setData(string $data): string
    {
        return CallApi::setData($data);
    }
}

