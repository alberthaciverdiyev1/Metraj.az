<?php

namespace App\Providers;

use App\Modules\Shared\Models\ActivityLog;
use Illuminate\Auth\Events\Failed as AuthFailed;
use Illuminate\Auth\Events\Login as AuthLogin;
use Illuminate\Auth\Events\Logout as AuthLogout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // =========================================================================
        // 1. AUTHENTICATION & SECURITY EVENTS
        // =========================================================================
        Event::listen(AuthLogin::class, function (AuthLogin $event) {
            try {
                $user = $event->user;
                ActivityLog::logAsync(
                    action: 'user_login',
                    modelType: get_class($user),
                    modelId: $user->id,
                    payload: [
                        'message' => "İstifadəçi sistemə daxil oldu ({$user->name})",
                        'user_email' => $user->email,
                        'user_name' => $user->name,
                    ],
                    userId: $user->id
                );
            } catch (\Throwable $e) {}
        });

        Event::listen(AuthLogout::class, function (AuthLogout $event) {
            try {
                if ($event->user) {
                    ActivityLog::logAsync(
                        action: 'user_logout',
                        modelType: get_class($event->user),
                        modelId: $event->user->id,
                        payload: [
                            'message' => "İstifadəçi sistemdən çıxış etdi ({$event->user->name})",
                            'user_email' => $event->user->email,
                        ],
                        userId: $event->user->id
                    );
                }
            } catch (\Throwable $e) {}
        });

        Event::listen(AuthFailed::class, function (AuthFailed $event) {
            try {
                $credentials = $event->credentials;
                unset($credentials['password']);

                ActivityLog::logAsync(
                    action: 'auth_failed',
                    payload: [
                        'message' => 'Uğursuz giriş cəhdi (Yanlış şifrə və ya email)',
                        'attempted_email' => $credentials['email'] ?? 'Naməlum',
                    ],
                    statusCode: 401
                );
            } catch (\Throwable $e) {}
        });

        Event::listen(Registered::class, function (Registered $event) {
            try {
                $user = $event->user;
                ActivityLog::logAsync(
                    action: 'user_registered',
                    modelType: get_class($user),
                    modelId: $user->id,
                    payload: [
                        'message' => "Yeni istifadəçi qeydiyyatdan keçdi ({$user->name})",
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                    ],
                    userId: $user->id
                );
            } catch (\Throwable $e) {}
        });

        Event::listen(PasswordReset::class, function (PasswordReset $event) {
            try {
                $user = $event->user;
                ActivityLog::logAsync(
                    action: 'password_reset',
                    modelType: get_class($user),
                    modelId: $user->id,
                    payload: [
                        'message' => "İstifadəçi şifrəsini yenilədi ({$user->name})",
                        'user_email' => $user->email,
                    ],
                    userId: $user->id
                );
            } catch (\Throwable $e) {}
        });

        // =========================================================================
        // 2. MODEL LIFECYCLE EVENTS (CREATE, UPDATE, DELETE)
        // =========================================================================
        $models = [
            \App\Modules\Property\Models\Property::class => 'Əmlak Elanı',
            \App\Modules\Shared\Models\User::class => 'İstifadəçi Hesabı',
            \App\Modules\Inquiry\Models\Inquiry::class => 'Müştəri Müraciəti (Inquiry)',
            \App\Modules\Blog\Models\Blog::class => 'Bloq Məqaləsi',
            \App\Modules\Roommate\Models\RoommateListing::class => 'Otaq Yoldaşı Elanı',
            \App\Modules\PropertyRequest\Models\PropertyRequest::class => 'Əmlak Tələbi (Axtarıram)',
            \App\Modules\Agency\Models\Agency::class => 'Agentlik',
            \App\Modules\Agency\Models\Agent::class => 'Rieltor / Agent',
            \App\Modules\Shared\Models\PageSeo::class => 'SEO Səhifə Tənzimləməsi',
            \App\Modules\Shared\Models\SeoSetting::class => 'Qlobal SEO Skriptləri',
            \App\Modules\Shared\Models\SiteSetting::class => 'Sayt Tənzimləmələri',
            \App\Modules\Location\Models\City::class => 'Şəhər',
            \App\Modules\Location\Models\District::class => 'Rayon / Bölgə',
            \App\Modules\Location\Models\Amenity::class => 'Əmlak Xüsusiyyəti (Amenity)',
            \App\Modules\Location\Models\Filter::class => 'Axtarış Filtri',
            \App\Modules\Location\Models\FilterOption::class => 'Filtr Seçimi',
        ];

        foreach ($models as $modelClass => $modelName) {
            if (!class_exists($modelClass)) {
                continue;
            }

            // CREATED
            $modelClass::created(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? ('#' . $model->id);

                    ActivityLog::logAsync(
                        action: 'model_created',
                        modelType: get_class($model),
                        modelId: $model->id,
                        payload: [
                            'message' => "Yeni {$modelName} yaradıldı: {$identity}",
                            'model_name' => $modelName,
                            'identity' => $identity,
                            'attributes' => $model->getAttributes(),
                        ]
                    );
                } catch (\Throwable $e) {}
            });

            // UPDATED
            $modelClass::updated(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? ('#' . $model->id);

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

                    ActivityLog::logAsync(
                        action: 'model_updated',
                        modelType: get_class($model),
                        modelId: $model->id,
                        payload: [
                            'message' => "{$modelName} redaktə edildi: {$identity}",
                            'model_name' => $modelName,
                            'identity' => $identity,
                            'changes' => $changes,
                        ]
                    );
                } catch (\Throwable $e) {}
            });

            // DELETED
            $modelClass::deleted(function ($model) use ($modelName) {
                try {
                    $identity = $model->title ?? $model->name ?? $model->email ?? ('#' . $model->id);

                    ActivityLog::logAsync(
                        action: 'model_deleted',
                        modelType: get_class($model),
                        modelId: $model->id,
                        payload: [
                            'message' => "{$modelName} silindi: {$identity}",
                            'model_name' => $modelName,
                            'identity' => $identity,
                        ]
                    );
                } catch (\Throwable $e) {}
            });
        }
    }
}
