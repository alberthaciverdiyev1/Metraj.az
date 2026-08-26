<?php

namespace App\Modules\Shared\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Qonaq ziyarətçilər üçün tam səhifə keşi.
 *
 * - Yalnız GET sorğuları və qonaq (auth()->guest()) istifadəçilər üçün.
 * - Giriş etmiş istifadəçilər hər zaman canlı render edilir (öz məlumatları/CSRF üçün).
 * - Flash mesajlar (success/error) varsa keşlənir ki, istifadəçiyə göstərilsin.
 * - `_cache_bust` parametri verilərsə keşlənir (test/manual yeniləmə üçün).
 */
trait CachesGuestPage
{
    protected function cacheGuestPage(Request $request, string $prefix, int $ttl, callable $render): \Illuminate\Http\Response
    {
        if (auth()->guest()
            && ! session()->has('success')
            && ! session()->has('error')
            && ! $request->has('_cache_bust')) {
            $key = 'page_cache:'.$prefix.':'.md5(
                $request->fullUrl().'|'.session('currency').'|'.app()->getLocale()
            );

            $html = Cache::remember($key, $ttl, $render);

            // Keşlənmiş səhifədəki CSRF tokenini cari sessiyanın tokeni ilə yenilə
            // (fərqli qonaq sessiyaları üçün form göndərimi 419 verməsin).
            $html = $this->refreshCsrfToken($html);

            return response($html);
        }

        return response($render());
    }

    /**
     * Keşlənmiş HTML-dəki köhnə CSRF tokenini cari sessiyanın tokeni ilə əvəz edir.
     * Fərqli qonaq sessiyaları üçün form göndəriminin 419 verməməsini təmin edir.
     */
    protected function refreshCsrfToken(string $html): string
    {
        $token = csrf_token();

        $html = preg_replace(
            '/<meta name="csrf-token" content="[^"]+">/',
            '<meta name="csrf-token" content="'.$token.'">',
            $html,
            1
        );

        $html = preg_replace(
            '/(name="_token" value=")[^"]+(")/',
            '$1'.$token.'$2',
            $html
        );

        return $html;
    }
}
