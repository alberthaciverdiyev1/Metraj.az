<!doctype html>
<html lang="{{ app()->getLocale() ?? 'az' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/components.css">

    @if(isset($css))
        @foreach($css as $file)
            <link rel="stylesheet" href="{{ str_starts_with($file, '/') ? $file : '/css/' . $file }}">
        @endforeach
    @endif
</head>
<body class="bg-[#F7F7F7]">
    @if(!isset($useLayout) || $useLayout !== false)
        @include('layouts.navbar')

        <div class="w-full relative z-0">
            <div class="flex mx-auto max-w-[1920px] px-3 sm:px-5 lg:px-7 gap-5 xl:gap-7 justify-center">
                <!-- Sol Reklam -->
                <aside class="hidden xl:block w-[210px] 2xl:w-[260px] 3xl:w-[280px] mt-6 shrink-0 relative z-10">
                    <div class="sticky top-24 z-10">
                        <img src="https://placehold.co/400x1300" alt="Sol Reklam" class="rounded-2xl shadow-sm w-full object-cover max-h-[calc(100vh-120px)]"/>
                    </div>
                </aside>

                <!-- Əsas Məzmun (Bütün Səhifələr Eyni Genişlikdə) -->
                <main class="flex-1 min-w-0 max-w-full">
                    @yield('content')
                </main>

                <!-- Sağ Reklam -->
                <aside class="hidden xl:block w-[210px] 2xl:w-[260px] 3xl:w-[280px] mt-6 shrink-0 relative z-10">
                    <div class="sticky top-24 z-10">
                        <img src="https://placehold.co/400x1300" alt="Sağ Reklam" class="rounded-2xl shadow-sm w-full object-cover max-h-[calc(100vh-120px)]"/>
                    </div>
                </aside>
            </div>
        </div>

        @include('layouts.footer')
    @else
        @yield('content')
    @endif

    @include('layouts.js')
</body>
</html>
