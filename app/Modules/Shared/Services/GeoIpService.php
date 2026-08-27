<?php

namespace App\Modules\Shared\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIpService
{
    /**
     * Resolve Geolocation details for a given IP address.
     * Caches results for 30 days to avoid duplicate API requests.
     */
    public static function resolve(?string $ip, ?string $cfCountry = null): array
    {
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.16.')) {
            return [
                'country_code' => $cfCountry ?: 'LOC',
                'country_name' => $cfCountry ? self::countryNameFromCode($cfCountry) : 'Lokal Şəbəkə / Server',
                'city' => 'Lokal',
                'region' => 'Daxili Şəbəkə',
                'latitude' => 35.1856, // Default Northern Cyprus lat
                'longitude' => 33.3823, // Default Northern Cyprus lon
                'isp' => 'Internal / Localhost',
            ];
        }

        $cacheKey = 'geoip_v2_' . md5($ip);

        return Cache::remember($cacheKey, 86400 * 30, function () use ($ip, $cfCountry) {
            try {
                // Try ip-api.com first
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city,lat,lon,isp");

                if ($response->successful() && $response->json('status') === 'success') {
                    $data = $response->json();
                    return [
                        'country_code' => strtoupper($data['countryCode'] ?? $cfCountry ?? 'XX'),
                        'country_name' => $data['country'] ?? self::countryNameFromCode($cfCountry),
                        'city' => $data['city'] ?? 'Naməlum',
                        'region' => $data['regionName'] ?? '',
                        'latitude' => isset($data['lat']) ? (float) $data['lat'] : null,
                        'longitude' => isset($data['lon']) ? (float) $data['lon'] : null,
                        'isp' => $data['isp'] ?? '',
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore timeout / failure
            }

            try {
                // Fallback to ipwhois.app
                $response = Http::timeout(2)->get("https://ipwhois.app/json/{$ip}");
                if ($response->successful() && $response->json('success') === true) {
                    $data = $response->json();
                    return [
                        'country_code' => strtoupper($data['country_code'] ?? $cfCountry ?? 'XX'),
                        'country_name' => $data['country'] ?? self::countryNameFromCode($cfCountry),
                        'city' => $data['city'] ?? 'Naməlum',
                        'region' => $data['region'] ?? '',
                        'latitude' => isset($data['latitude']) ? (float) $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? (float) $data['longitude'] : null,
                        'isp' => $data['isp'] ?? '',
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            // Fallback to Cloudflare header if available
            return [
                'country_code' => strtoupper($cfCountry ?? 'XX'),
                'country_name' => self::countryNameFromCode($cfCountry),
                'city' => 'Naməlum',
                'region' => '',
                'latitude' => null,
                'longitude' => null,
                'isp' => '',
            ];
        });
    }

    /**
     * Parse User Agent into Device Type, Browser, and Operating System.
     */
    public static function parseUserAgent(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return [
                'device_type' => 'Naməlum',
                'browser' => 'Naməlum',
                'os' => 'Naməlum',
            ];
        }

        // 1. Detect Bots
        if (preg_match('/(googlebot|bingbot|yandexbot|duckduckbot|baiduspider|sogou|exabot|facebot|facebookexternalhit|ia_archiver|crawler|spider|bot)/i', $userAgent, $m)) {
            return [
                'device_type' => 'Bot',
                'browser' => ucfirst($m[1]),
                'os' => 'Axtarış Robotu',
            ];
        }

        // 2. Detect Device Type
        $deviceType = 'Desktop';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/(mobi|iphone|ipod|blackberry|opera mini|iemobile|mobile)/i', $userAgent)) {
            $deviceType = 'Mobile';
        }

        // 3. Detect Operating System
        $os = 'Digər ƏS';
        if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            $os = 'iOS';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/Windows NT 10.0/i', $userAgent)) {
            $os = 'Windows 10/11';
        } elseif (preg_match('/Windows/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac OS X/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        }

        // 4. Detect Browser
        $browser = 'Digər';
        if (preg_match('/Edg\/([0-9\.]+)/i', $userAgent, $m)) {
            $browser = 'Edge ' . explode('.', $m[1])[0];
        } elseif (preg_match('/OPR\/([0-9\.]+)/i', $userAgent, $m)) {
            $browser = 'Opera ' . explode('.', $m[1])[0];
        } elseif (preg_match('/Chrome\/([0-9\.]+)/i', $userAgent, $m)) {
            $browser = 'Chrome ' . explode('.', $m[1])[0];
        } elseif (preg_match('/Safari\/([0-9\.]+)/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Firefox\/([0-9\.]+)/i', $userAgent, $m)) {
            $browser = 'Firefox ' . explode('.', $m[1])[0];
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
        ];
    }

    /**
     * Get flag emoji from country code.
     */
    public static function flagEmoji(?string $countryCode): string
    {
        if (empty($countryCode) || $countryCode === 'XX' || $countryCode === 'LOC') {
            return '🌐';
        }

        $code = strtoupper($countryCode);
        if ($code === 'CY' || $code === 'NC') {
            return '🇨🇾';
        }
        if ($code === 'TR') {
            return '🇹🇷';
        }
        if ($code === 'AZ') {
            return '🇦🇿';
        }
        if ($code === 'RU') {
            return '🇷🇺';
        }
        if ($code === 'GB' || $code === 'UK') {
            return '🇬🇧';
        }
        if ($code === 'US') {
            return '🇺🇸';
        }
        if ($code === 'DE') {
            return '🇩🇪';
        }
        if ($code === 'UA') {
            return '🇺🇦';
        }
        if ($code === 'KZ') {
            return '🇰🇿';
        }
        if ($code === 'IR') {
            return '🇮🇷';
        }

        // Standard Unicode regional indicator symbols
        if (strlen($code) === 2) {
            $first = ord($code[0]) - ord('A') + 0x1F1E6;
            $second = ord($code[1]) - ord('A') + 0x1F1E6;
            return mb_chr($first, 'UTF-8') . mb_chr($second, 'UTF-8');
        }

        return '🌐';
    }

    /**
     * Convert 2-letter country code to localized name.
     */
    public static function countryNameFromCode(?string $code): string
    {
        if (empty($code)) {
            return 'Naməlum Ölkə';
        }

        $map = [
            'CY' => 'Şimali Kipr / Kipr',
            'NC' => 'Şimali Kipr (KKTC)',
            'TR' => 'Türkiyə',
            'AZ' => 'Azərbaycan',
            'RU' => 'Rusiya',
            'GB' => 'Böyük Britaniya',
            'US' => 'ABŞ',
            'DE' => 'Almaniya',
            'UA' => 'Ukrayna',
            'KZ' => 'Qazaxıstan',
            'UZ' => 'Özbəkistan',
            'GE' => 'Gürcüstan',
            'IR' => 'İran',
            'AE' => 'BƏƏ (Dubay)',
            'LOC' => 'Lokal Şəbəkə / Server',
        ];

        return $map[strtoupper($code)] ?? strtoupper($code);
    }
}
