<!doctype html>
<html lang="{{ app()->getLocale() ?? 'az' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $curPageSeo = $currentPageSeo ?? \App\Modules\Shared\Models\PageSeo::findForCurrentRoute();
        $seoConf = $seoSetting ?? \App\Modules\Shared\Models\SeoSetting::current();

        $resolvedTitle = View::hasSection('title') ? View::getSection('title') : ($title ?? ($curPageSeo?->getTrans('title') ?: ($seoConf?->getTrans('default_meta_title') ?: 'KibrisKare.com')));
        $resolvedDescription = ($metaDescription ?? null) ?? ($curPageSeo?->getTrans('description') ?: ($seoConf?->getTrans('default_meta_description') ?: ''));
        $resolvedKeywords = ($metaKeywords ?? null) ?? ($curPageSeo?->getTrans('keywords') ?: ($seoConf?->getTrans('default_meta_keywords') ?: ''));
        $resolvedOgImage = ($ogImage ?? null) ?? ($curPageSeo?->og_image ?: ($seoConf?->og_image ?: asset('images/kibriskarelogo1.png')));
    @endphp

    <title>{{ $resolvedTitle }}</title>

    @if($resolvedDescription)
        <meta name="description" content="{{ $resolvedDescription }}"/>
    @endif
    @if($resolvedKeywords)
        <meta name="keywords" content="{{ $resolvedKeywords }}"/>
    @endif

    <!-- Open Graph / Social Meta -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $resolvedTitle }}">
    @if($resolvedDescription)
        <meta property="og:description" content="{{ $resolvedDescription }}">
    @endif
    @if($resolvedOgImage)
        <meta property="og:image" content="{{ $resolvedOgImage }}">
    @endif

    @if(!empty($setting->favicon ?? $setting['favicon'] ?? null))
        <link rel="shortcut icon" href="{{ $setting->favicon ?? $setting['favicon'] ?? '' }}" type="">
    @endif

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="" crossorigin=""/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/app-legacy.css') }}?v=1.3">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}?v=1.3">

    @if(isset($css))
        @foreach($css as $file)
            <link rel="stylesheet" href="{{ str_starts_with($file, '/') ? $file : '/css/' . $file }}">
        @endforeach
    @endif

    @stack('styles')

    {{-- Global <head> Scripts (Raw HTML/JS from Admin) --}}
    @if(!empty($seoConf?->head_scripts))
        {!! $seoConf->head_scripts !!}
    @endif
</head>
<body class="bg-[#F7F7F7] pb-20 md:pb-0">
    {{-- Global <body> Opening Scripts (Raw HTML/JS from Admin, e.g. GTM noscript) --}}
    @if(!empty($seoConf?->body_scripts))
        {!! $seoConf->body_scripts !!}
    @endif

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

        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 mt-14 mb-6">
            @include('components.quick-searches')
        </div>

        @include('layouts.footer')
    @else
        @yield('content')
    @endif

    @include('layouts.js')
    @stack('scripts')

    {{-- Global Footer / </body> Scripts (Raw HTML/JS from Admin, e.g. Live chat, widgets) --}}
    @if(!empty($seoConf?->footer_scripts))
        {!! $seoConf->footer_scripts !!}
    @endif
</body>
</html>
