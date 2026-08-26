<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Agency\Enums\AgencyStatus;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Shared\Concerns\CachesGuestPage;
use App\Modules\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    use CachesGuestPage;

    /**
     * İstifadəçinin roluna uyğun yönləndirmə ünvanını təyin edir:
     * - Admin -> /admin
     * - Agentlik (Agency) və ya Rieltor (Agent) -> /agency (Filament paneli)
     * - Normal istifadəçi -> / (və ya /dashboard)
     */
    public static function getRedirectUrlForUser(User $user): string
    {
        if ($user->isAdmin()) {
            return '/admin';
        }

        if ($user->agencies()->exists() || $user->agent()->exists()) {
            return '/agency';
        }

        return '/';
    }

    /**
     * Giriş (Login) səhifəsini göstərir.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect(self::getRedirectUrlForUser(Auth::user()));
        }

        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Daxil ol'), 'url' => null],
        ];

        return view('pages.auth.login', compact('breadcrumbs'));
    }

    /**
     * Qeydiyyat (Register) səhifəsini göstərir.
     */
    public function showRegister(): \Illuminate\Http\Response|View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect(self::getRedirectUrlForUser(Auth::user()));
        }

        // Qonaqlar üçün tam səhifə keşi. Validasiya xətaları/old() girişi varsa keşlənir
        // (form səhv göndərimindən sonra geri qayıdanda xətalar görünsün).
        if (auth()->guest()
            && ! session()->has('errors')
            && ! request()->has('_cache_bust')) {
            $key = 'page_cache:register:'.md5(app()->getLocale());

            $html = \Illuminate\Support\Facades\Cache::remember($key, 60, fn () => $this->renderRegister());

            return response($this->refreshCsrfToken($html));
        }

        return response($this->renderRegister());
    }

    protected function renderRegister(): string
    {
        $agencies = Agency::where('status', AgencyStatus::Active)
            ->orderBy('name')
            ->get(['id', 'name']);

        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Qeydiyyat'), 'url' => null],
        ];

        return view('pages.auth.register', compact('agencies', 'breadcrumbs'))->render();
    }

    /**
     * Giriş əməliyyatı (AJAX və standart HTTP dəstəyi ilə).
     */
    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-poçt ünvanınızı daxil edin.',
            'email.email' => 'Düzgün e-poçt formatı daxil edin.',
            'password.required' => 'Şifrənizi daxil edin.',
        ]);

        $remember = $request->boolean('remember');
        $oldSessionId = $request->hasSession() ? $request->session()->getId() : null;

        if (Auth::attempt($credentials, $remember)) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            /** @var User $user */
            $user = Auth::user();
            $this->migrateSessionFavoritesAndCompares($user, $oldSessionId);
            $redirectUrl = self::getRedirectUrlForUser($user);

            $role = 'user';
            if ($user->isAdmin()) {
                $role = 'admin';
            } elseif ($user->agencies()->exists()) {
                $role = 'agency';
            } elseif ($user->agent()->exists()) {
                $role = 'agent';
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uğurla daxil oldunuz! Yönləndirilirsiniz...',
                    'redirect' => $redirectUrl,
                    'role' => $role,
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Daxil etdiyiniz e-poçt və ya şifrə yanlışdır.',
                'errors' => [
                    'email' => ['Daxil etdiyiniz e-poçt və ya şifrə yanlışdır.'],
                ],
            ], 422);
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Daxil etdiyiniz e-poçt və ya şifrə yanlışdır.']);
    }

    /**
     * Qeydiyyat əməliyyatı (Fərdi istifadəçi, Rieltor və Agentlik üçün).
     */
    public function register(Request $request): JsonResponse|RedirectResponse
    {
        $roleType = $request->input('role_type', 'user');

        $rules = [
            'role_type' => ['required', 'in:user,agent,agency'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        if ($roleType === 'agency') {
            $rules['agency_name'] = ['required', 'string', 'max:255'];
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:50'];
            $rules['whatsapp'] = ['nullable', 'string', 'max:50'];
            $rules['address'] = ['nullable', 'string', 'max:255'];
        } elseif ($roleType === 'agent') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:50'];
            $rules['whatsapp'] = ['nullable', 'string', 'max:50'];
            $rules['agency_id'] = ['nullable', 'integer', 'exists:agencies,id'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        $messages = [
            'agency_name.required' => 'Agentliyin (şirkətin) adını daxil edin.',
            'name.required' => 'Ad və soyadınızı daxil edin.',
            'email.required' => 'E-poçt ünvanınızı daxil edin.',
            'email.email' => 'Düzgün e-poçt ünvanı daxil edin.',
            'email.unique' => 'Bu e-poçt ünvanı artıq qeydiyyatdan keçib.',
            'phone.required' => 'Əlaqə nömrənizi daxil edin.',
            'password.required' => 'Şifrə daxil edin.',
            'password.min' => 'Şifrə ən azı 6 simvoldan ibarət olmalıdır.',
            'password.confirmed' => 'Şifrə təkrarı uyğun gəlmir.',
        ];

        $validated = $request->validate($rules, $messages);

        /** @var User $user */
        $user = DB::transaction(function () use ($validated, $roleType) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if ($roleType === 'agency') {
                Agency::create([
                    'owner_id' => $user->id,
                    'name' => $validated['agency_name'],
                    'slug' => Str::slug($validated['agency_name']) . '-' . Str::random(5),
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'whatsapp' => $validated['whatsapp'] ?? $validated['phone'],
                    'address' => $validated['address'] ?? null,
                    'status' => AgencyStatus::Active,
                    'is_verified' => false,
                ]);
            } elseif ($roleType === 'agent') {
                Agent::create([
                    'user_id' => $user->id,
                    'agency_id' => !empty($validated['agency_id']) ? (int) $validated['agency_id'] : null,
                    'phone' => $validated['phone'],
                    'whatsapp' => $validated['whatsapp'] ?? $validated['phone'],
                    'position' => 'Rieltor',
                    'is_active' => true,
                ]);
            }

            return $user;
        });

        // İstifadəçini sistemə daxil edirik
        $oldSessionId = $request->hasSession() ? $request->session()->getId() : null;
        Auth::login($user);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }
        $this->migrateSessionFavoritesAndCompares($user, $oldSessionId);

        $redirectUrl = self::getRedirectUrlForUser($user);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Qeydiyyat uğurla tamamlandı! Yönləndirilirsiniz...',
                'redirect' => $redirectUrl,
                'role' => $roleType,
            ]);
        }

        return redirect($redirectUrl)->with('success', 'Qeydiyyat uğurla tamamlandı!');
    }

    /**
     * Çıxış əməliyyatı.
     */
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        Auth::logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Çıxış edildi.',
                'redirect' => '/',
            ]);
        }

        return redirect('/');
    }

    /**
     * Qonaq sessiyasında toplanmış favorit və müqayisələri daxil olan istifadəçiyə bağlayır.
     */
    protected function migrateSessionFavoritesAndCompares(User $user, ?string $oldSessionId): void
    {
        if (! $oldSessionId) return;

        \App\Modules\Property\Models\Favorite::where('session_id', $oldSessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id, 'session_id' => null]);

        \App\Modules\Property\Models\Compare::where('session_id', $oldSessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id, 'session_id' => null]);
    }
}
