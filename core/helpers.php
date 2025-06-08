<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('get_data')) {
    function get_data(string $url, array $params = [], bool $enum = false, bool $allData = false): array
    {
        $queryParams = $allData ? $params : array_merge(['page' => 1], $params);

        $fullUrl = rtrim(config('app.api_url'), '/') . '/api' . $url;

        $response = Http::get($fullUrl, $queryParams);

        return $enum ? $response->json() : $response->json('data');
    }
}

if (!function_exists('post_data')) {

    function post_data(string $url, array $payload = [])
    {
        $fullUrl = rtrim(config('app.api_url'), '/') . '/api' . $url;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($fullUrl, $payload);

        if ($response->failed()) {
            return [
                'status' => $response->status(),
                'message' => $response->json('message') ?? 'API error',
                'errors' => $response->json('errors') ?? [],
            ];
        }

        return $response->json();
    }

}


if (!function_exists('put_data')) {
    function put_data(string $url, array $payload = []): array
    {
        $fullUrl = rtrim(config('app.api_url'), '/') . '/api' . $url;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($fullUrl, $payload);

        return $response->json('data') ?? [];
    }
}

if (!function_exists('patch_data')) {
    function patch_data(string $url, array $payload = []): array
    {
        $fullUrl = rtrim(config('app.api_url'), '/') . '/api' . $url;

        $response = Http::patch($fullUrl, $payload);

        return $response->json('data') ?? [];
    }
}

if (!function_exists('delete_data')) {
    function delete_data(string $url, array $payload = []): array
    {
        $fullUrl = rtrim(config('app.api_url'), '/') . '/api' . $url;

        $response = Http::withBody(json_encode($payload), 'application/json')
            ->delete($fullUrl);

        return $response->json('data') ?? [];
    }
}

if (!function_exists('setData')) {
    function setData(string $data): string
    {
        return htmlspecialchars(trim($data));
    }
}

//// GET
//$data = get_data('users', ['page' => 2]);
//
//// POST
//$response = post_data('users', ['name' => 'Ali', 'email' => 'ali@example.com']);
//
//// PUT
//$response = put_data('users/5', ['email' => 'ali@updated.com']);
//
//// PATCH
//$response = patch_data('users/5', ['status' => 'active']);
//
//// DELETE
//$response = delete_data('users/5');

