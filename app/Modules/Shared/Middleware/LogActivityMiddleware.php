<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Shared\Models\ActivityLog;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Do not log asset requests, Livewire updates, debug paths, or background tasks
        $url = $request->getRequestUri();
        if (
            $request->isXmlHttpRequest() || 
            str_contains($url, '/livewire/') || 
            str_contains($url, '/assets/') || 
            str_contains($url, '/storage/') ||
            str_contains($url, '/_debugbar/') ||
            str_contains($url, '/telescope/') ||
            str_contains($url, '/filament/assets/')
        ) {
            return $response;
        }

        $userAgent = $request->userAgent() ?? '';
        $botName = null;
        
        // Detect Search Engine Bots
        if (preg_match('/(googlebot|bingbot|yandexbot|duckduckbot|baiduspider|sogou|exabot|facebot|facebookexternalhit|ia_archiver)/i', $userAgent, $matches)) {
            $botName = ucfirst($matches[1]);
        }

        // Determine action name
        $action = 'page_view';
        $isAdmin = str_starts_with($request->getPathInfo(), '/admin');

        if ($botName) {
            $action = 'bot_visit';
        } elseif ($isAdmin) {
            if ($request->isMethod('GET')) {
                $action = 'admin_view';
            } else {
                $action = 'admin_action';
            }
        } else {
            if ($request->isMethod('POST')) {
                $action = 'form_submit';
            } elseif ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
                $action = 'data_update';
            } elseif ($request->isMethod('DELETE')) {
                $action = 'data_delete';
            }
        }

        // Build Payload
        $payload = [
            'status_code' => $response->getStatusCode(),
        ];

        if ($botName) {
            $payload['bot_name'] = $botName;
        }

        // Log query parameters for GET requests (useful for search/filter tracking)
        if ($request->isMethod('GET') && !empty($request->query())) {
            $payload['query'] = $request->query();
        }

        // Log input payload for state-changing requests (excluding sensitive or file fields)
        if (!$request->isMethod('GET')) {
            $payload['input'] = $request->except([
                'password', 
                'password_confirmation', 
                '_token', 
                'image', 
                'images', 
                'cover_image', 
                'avatar', 
                'logo', 
                'banner',
                'file',
                'files'
            ]);
        }

        // Store user role if logged in
        if (auth()->check()) {
            $user = auth()->user();
            $payload['user_role'] = $user->email === \App\Modules\Shared\Models\User::ADMIN_EMAIL ? 'Admin' : ($user->agent ? 'Rieltor' : 'İstifadəçi');
        }

        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'action' => $action,
                'payload' => $payload,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('Activity Log Middleware Error: ' . $e->getMessage());
        }

        return $response;
    }
}
