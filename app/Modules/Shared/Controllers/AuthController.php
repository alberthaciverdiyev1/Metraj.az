<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * AJAX (JS fetch) ilə login.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Uğurla daxil oldunuz ✅',
                'redirect' => '/dashboard',
                'role' => $user->email === \App\Modules\Shared\Models\User::ADMIN_EMAIL ? 'admin' : 'user',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email və ya şifrə səhvdir ❌',
        ], 422);
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Çıxış edildi',
                'redirect' => '/',
            ]);
        }

        return redirect('/');
    }
}
