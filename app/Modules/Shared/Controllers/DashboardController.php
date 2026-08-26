<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function dashboard(): RedirectResponse
    {
        return $this->redirectByRole('/admin', '/agency');
    }

    public function profile(): RedirectResponse
    {
        return $this->redirectByRole('/admin/profile', '/agency/profile');
    }

    public function myProperties(): RedirectResponse
    {
        return $this->redirectByRole('/admin/properties', '/agency/properties');
    }

    /**
     * Admin istifadəçi admin panelinə, digərləri agentlik panelinə yönləndirilir.
     * Giriş etməyənlər login səhifəsinə aparılır.
     */
    private function redirectByRole(string $adminPath, string $agencyPath): RedirectResponse
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->isAdmin()) {
            return redirect($adminPath);
        }

        return redirect($agencyPath);
    }
}
