@extends('layouts.app')

@section('title', ($badge ?? 'Xəta') . ' - ' . ($title ?? 'KibrisKare.com'))

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12 sm:py-20">
    <div class="max-w-xl w-full text-center">
        {{-- Status Icon / Badge --}}
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wide uppercase shadow-2xs mb-6 {{ $badgeClass ?? 'bg-orange-50 text-orange-600 border border-orange-200' }}">
            <i class="{{ $icon ?? 'fa-solid fa-triangle-exclamation' }}"></i>
            <span>{{ $badge ?? 'Xəta' }}</span>
        </div>

        {{-- Big Stylized Number --}}
        <div class="relative select-none my-2">
            <span class="text-7xl sm:text-9xl font-extrabold tracking-tight {{ $codeColor ?? 'text-gray-900' }} opacity-90 drop-shadow-xs">
                {{ $code ?? '404' }}
            </span>
            <div class="absolute inset-0 flex items-center justify-center blur-2xl -z-10 opacity-30">
                <span class="text-8xl sm:text-9xl font-black text-orange-500">
                    {{ $code ?? '404' }}
                </span>
            </div>
        </div>

        {{-- Main Headline --}}
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight mt-2 mb-3">
            {{ $title ?? __('errors.404.title') }}
        </h1>

        {{-- Explanatory Description --}}
        <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-md mx-auto mb-8">
            {{ $description ?? __('errors.404.description') }}
        </p>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-600 active:scale-[0.98] text-white font-semibold text-sm rounded-2xl shadow-sm hover:shadow-md transition">
                <i class="fa-solid fa-house text-xs"></i>
                <span>{{ __('errors.back_to_home') }}</span>
            </a>

            @if(isset($showRefresh) && $showRefresh)
                <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 font-semibold text-sm rounded-2xl border border-gray-200 shadow-2xs hover:border-gray-300 transition">
                    <i class="fa-solid fa-rotate-right text-xs text-gray-500"></i>
                    <span>{{ __('errors.refresh_page') }}</span>
                </button>
            @else
                <a href="{{ route('listing') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 font-semibold text-sm rounded-2xl border border-gray-200 shadow-2xs hover:border-gray-300 transition">
                    <i class="fa-solid fa-magnifying-glass text-xs text-gray-500"></i>
                    <span>{{ __('errors.browse_listings') }}</span>
                </a>
            @endif

            <button type="button" onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='/'; }" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 text-gray-500 hover:text-gray-800 text-sm font-medium transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>{{ __('errors.back_previous') }}</span>
            </button>
        </div>

        {{-- Quick Nav Suggestions --}}
        <div class="mt-12 pt-8 border-t border-gray-200/80">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                Faydalı Bölmələr
            </div>
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs font-medium">
                <a href="{{ route('listing.path1', ['first' => 'satilik']) }}" class="px-3 py-1.5 bg-white hover:bg-orange-50 hover:text-orange-600 border border-gray-200/80 rounded-xl text-gray-700 transition">
                    🏷️ Satılıq Əmlaklar
                </a>
                <a href="{{ route('listing.path2', ['first' => 'kira', 'second' => 'ayliq']) }}" class="px-3 py-1.5 bg-white hover:bg-orange-50 hover:text-orange-600 border border-gray-200/80 rounded-xl text-gray-700 transition">
                    🔑 Kirayə Mənzillər
                </a>
                <a href="{{ route('requests.index') }}" class="px-3 py-1.5 bg-white hover:bg-orange-50 hover:text-orange-600 border border-gray-200/80 rounded-xl text-gray-700 transition">
                    📢 Əmlak Tələbləri
                </a>
                <a href="{{ route('contact') }}" class="px-3 py-1.5 bg-white hover:bg-orange-50 hover:text-orange-600 border border-gray-200/80 rounded-xl text-gray-700 transition">
                    📞 Əlaqə
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
