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

        // Determine action name based on route method
        $action = 'page_view';
        if ($request->isMethod('POST')) {
            $action = 'form_submit';
        } elseif ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $action = 'data_update';
        } elseif ($request->isMethod('DELETE')) {
            $action = 'data_delete';
        }

        // Fetch payload (excluding sensitive or large file fields)
        $payload = null;
        if (!$request->isMethod('GET')) {
            $payload = $request->except([
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

        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'action' => $action,
                'payload' => $payload,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('Activity Log Error: ' . $e->getMessage());
        }

        return $response;
    }
}
