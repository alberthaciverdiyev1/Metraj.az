<!doctype html>
<html lang="{{ app()->getLocale() ?? 'az' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @if(!empty($setting->favicon ?? $setting['favicon'] ?? null))
        <link rel="shortcut icon" href="{{ $setting->favicon ?? $setting['favicon'] ?? '' }}" type="">
    @endif

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="" crossorigin=""/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>{{ $title ?? 'Metraj.az' }}</title>

    @if(isset($seo))
        <meta name="description" content="{{ $seo->description ?? $seo['description'] ?? '' }}"/>
        <meta name="keywords" content="{{ $seo->meta_tags ?? $seo['meta_tags'] ?? '' }}"/>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .toastify {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
        }
        .toastify .toast-close {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-left: 12px !important;
            padding: 0 !important;
            opacity: 0.7 !important;
            font-size: 14px !important;
            line-height: 1 !important;
            color: #94a3b8 !important;
            cursor: pointer !important;
            transition: opacity 0.2s, color 0.2s !important;
        }
        .toastify .toast-close:hover {
            opacity: 1 !important;
            color: #ffffff !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/components.css">

    @if(isset($css))
        @foreach($css as $file)
            <link rel="stylesheet" href="{{ str_starts_with($file, '/') ? $file : '/css/' . $file }}">
        @endforeach
    @endif

    @stack('styles')
</head>
<body class="bg-[#F7F7F7] pb-20 md:pb-0">
    @if(!isset($useLayout) || $useLayout !== false)
        @include('layouts.navbar')

        <div class="w-full">
            <div class="flex flex-nowrap w-full px-2 sm:px-3 xl:px-4 gap-3 xl:gap-5 justify-between items-start">
                <!-- Sol Reklam -->
                <x-ads.sidebar-ad position="left" />

                <!-- Əsas Məzmun (Bütün Səhifələr Eyni Genişlikdə) -->
                <main class="flex-1 min-w-0 w-full">
                    @yield('content')
                </main>

                <!-- Sağ Reklam -->
                <x-ads.sidebar-ad position="right" />
            </div>
        </div>

        @include('layouts.footer')
    @else
        @yield('content')
    @endif

    @include('layouts.js')
    @stack('scripts')
</body>
</html>
