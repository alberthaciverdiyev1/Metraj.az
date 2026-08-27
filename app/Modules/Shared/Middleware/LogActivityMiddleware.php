<?php

namespace App\Modules\Shared\Middleware;

use App\Modules\Shared\Jobs\ProcessActivityLogJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        // Filter out noise: assets, heartbeat/ping, debug tools, filament asset polling
        $path = $request->getPathInfo();
        $uri = $request->getRequestUri();

        if (
            str_contains($uri, '/filament/assets/') ||
            str_contains($uri, '/livewire/livewire.js') ||
            str_contains($uri, '/livewire/update') ||
            str_contains($uri, '/assets/') ||
            str_contains($uri, '/storage/') ||
            str_contains($uri, '/vendor/') ||
            str_contains($uri, '/favicon.ico') ||
            str_contains($uri, '/robots.txt') ||
            str_contains($uri, '/_debugbar/') ||
            str_contains($uri, '/telescope/')
        ) {
            return $response;
        }

        $durationMs = (int) round((microtime(true) - $startTime) * 1000);
        $userAgent = $request->userAgent() ?? '';
        $realIp = $request->header('CF-Connecting-IP') ?? $request->header('X-Forwarded-For') ?? $request->ip();
        $cfCountry = $request->header('CF-IPCountry');

        // Search Engine Bot Detection
        $botName = null;
        if (preg_match('/(googlebot|bingbot|yandexbot|duckduckbot|baiduspider|sogou|exabot|facebot|facebookexternalhit|ia_archiver)/i', $userAgent, $matches)) {
            $botName = ucfirst($matches[1]);
        }

        // Categorize action
        $isAdmin = str_starts_with($path, '/admin');
        $isAgency = str_starts_with($path, '/agency');
        $statusCode = $response->getStatusCode();

        if ($botName) {
            $action = 'bot_visit';
        } elseif ($statusCode >= 500) {
            $action = 'server_error';
        } elseif ($statusCode === 404) {
            $action = 'not_found_404';
        } elseif ($isAdmin) {
            $action = $request->isMethod('GET') ? 'admin_view' : 'admin_action';
        } elseif ($isAgency) {
            $action = $request->isMethod('GET') ? 'agency_view' : 'agency_action';
        } else {
            if ($request->isMethod('GET')) {
                if (!empty($request->query('keyword')) || !empty($request->query('deal_type')) || !empty($request->query('city_id'))) {
                    $action = 'search_filter';
                } elseif (str_starts_with($path, '/elan/') || str_starts_with($path, '/property/')) {
                    $action = 'property_view';
                } elseif (str_starts_with($path, '/blog/')) {
                    $action = 'blog_view';
                } else {
                    $action = 'page_view';
                }
            } elseif ($request->isMethod('POST')) {
                $action = 'form_submit';
            } elseif ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
                $action = 'data_update';
            } elseif ($request->isMethod('DELETE')) {
                $action = 'data_delete';
            } else {
                $action = 'request';
            }
        }

        // Build structured payload
        $payload = [];

        if ($botName) {
            $payload['bot_name'] = $botName;
        }

        // Capture search criteria / query parameters
        if (!empty($request->query())) {
            $payload['query_params'] = $request->query();
        }

        // Capture safe input data on POST/PUT/DELETE
        if (!$request->isMethod('GET')) {
            $payload['input'] = $request->except([
                'password',
                'password_confirmation',
                'current_password',
                '_token',
                'image',
                'images',
                'cover_image',
                'avatar',
                'logo',
                'banner',
                'file',
                'files',
            ]);
        }

        // Capture user authentication and role
        if (auth()->check()) {
            $user = auth()->user();
            $payload['user_name'] = $user->name;
            $payload['user_email'] = $user->email;
            $payload['user_role'] = $user->email === \App\Modules\Shared\Models\User::ADMIN_EMAIL ? 'Admin' : ($user->agent ? 'Rieltor' : 'İstifadəçi');
        }

        $logData = [
            'user_id' => auth()->id(),
            'ip_address' => $realIp,
            'cf_country' => $cfCountry,
            'user_agent' => $userAgent,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
            'action' => $action,
            'model_type' => null,
            'model_id' => null,
            'payload' => $payload,
            'duration_ms' => $durationMs,
            'status_code' => $statusCode,
            'created_at' => now()->toDateTimeString(),
        ];

        try {
            // Asynchronous execution after response has been sent to client (0ms overhead)
            dispatch(new ProcessActivityLogJob($logData))->afterResponse();
        } catch (\Throwable $e) {
            // Fail silently
        }

        return $response;
    }
}
