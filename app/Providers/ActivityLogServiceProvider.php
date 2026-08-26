<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $models = [
            \App\Modules\Property\Models\Property::class => 'Əmlak',
            \App\Modules\Shared\Models\User::class => 'İstifadəçi',
            \App\Modules\Inquiry\Models\Inquiry::class => 'Müştəri Müraciəti',
            \App\Modules\Blog\Models\Blog::class => 'Bloq Məqaləsi',
            \App\Modules\Roommate\Models\RoommateListing::class => 'Otaq Yoldaşı Elanı',
            \App\Modules\PropertyRequest\Models\PropertyRequest::class => 'Əmlak Tələbi',
            \App\Modules\Agency\Models\Agency::class => 'Agentlik',
            \App\Modules\Agency\Models\Agent::class => 'Agent / Rieltor',
        ];

        foreach ($models as $modelClass => $modelName) {
            if (!class_exists($modelClass)) {
                continue;
            }

            // Created
            $modelClass::created(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? $model->id;
                    
                    \App\Modules\Shared\Models\ActivityLog::create([
                        'user_id' => auth()->id(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'action' => 'create_model',
                        'model_type' => get_class($model),
                        'model_id' => $model->id,
                        'payload' => [
                            'model_name' => $modelName,
                            'identity' => $identity,
                            'attributes' => $model->getAttributes(),
                        ],
                        'created_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    logger()->error('Log Created Error: ' . $e->getMessage());
                }
            });

            // Updated
            $modelClass::updated(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? $model->id;
                    
                    $changes = [];
                    foreach ($model->getChanges() as $key => $value) {
                        if (in_array($key, ['updated_at', 'password', 'remember_token'])) {
                            continue;
                        }
                        $changes[$key] = [
                            'old' => $model->getOriginal($key),
                            'new' => $value,
                        ];
                    }

                    if (empty($changes)) {
                        return;
                    }

                    \App\Modules\Shared\Models\ActivityLog::create([
                        'user_id' => auth()->id(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'action' => 'update_model',
                        'model_type' => get_class($model),
                        'model_id' => $model->id,
                        'payload' => [
                            'model_name' => $modelName,
                            'identity' => $identity,
                            'changes' => $changes,
                        ],
                        'created_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    logger()->error('Log Updated Error: ' . $e->getMessage());
                }
            });

            // Deleted
            $modelClass::deleted(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? $model->id;

                    \App\Modules\Shared\Models\ActivityLog::create([
                        'user_id' => auth()->id(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'action' => 'delete_model',
                        'model_type' => get_class($model),
                        'model_id' => $model->id,
                        'payload' => [
                            'model_name' => $modelName,
                            'identity' => $identity,
                        ],
                        'created_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    logger()->error('Log Deleted Error: ' . $e->getMessage());
                }
            });
        }

        // Login Event
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            try {
                \App\Modules\Shared\Models\ActivityLog::create([
                    'user_id' => $event->user->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'method' => request()->method(),
                    'url' => request()->fullUrl(),
                    'action' => 'login',
                    'payload' => [
                        'email' => $event->user->email,
                        'name' => $event->user->name,
                    ],
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Log Login Error: ' . $e->getMessage());
            }
        });

        // Logout Event
        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            try {
                if ($event->user) {
                    \App\Modules\Shared\Models\ActivityLog::create([
                        'user_id' => $event->user->id,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'action' => 'logout',
                        'payload' => [
                            'email' => $event->user->email,
                            'name' => $event->user->name,
                        ],
                        'created_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                logger()->error('Log Logout Error: ' . $e->getMessage());
            }
        });

        // Failed Login Event
        Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            try {
                \App\Modules\Shared\Models\ActivityLog::create([
                    'user_id' => null,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'method' => request()->method(),
                    'url' => request()->fullUrl(),
                    'action' => 'failed_login',
                    'payload' => [
                        'attempted_email' => $event->credentials['email'] ?? 'unknown',
                    ],
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Log Failed Login Error: ' . $e->getMessage());
            }
        });
    }
}
