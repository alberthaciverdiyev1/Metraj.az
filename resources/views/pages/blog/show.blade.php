@extends('layouts.app')

@section('title', ($blog->title ?? '') . ' - Bloq - Metraj.az')

@section('content')
<div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])

    {{-- ==================== BLOG HEADER ==================== --}}
    <article class="bg-white rounded-3xl shadow-sm border border-gray-100 mt-4 sm:mt-6 overflow-hidden">
        @if($blog->cover_image)
            <div class="relative h-56 sm:h-80 lg:h-96 overflow-hidden">
                <img src="{{ $blog->cover_image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                @if($blog->category)
                    <span class="absolute top-4 left-4 bg-[var(--primary)] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">
                        {{ $blog->category }}
                    </span>
                @endif
            </div>
        @else
            <div class="relative h-40 sm:h-56 bg-gradient-to-r from-[var(--primary)] via-orange-400 to-orange-300 flex items-center justify-center">
                @if($blog->category)
                    <span class="bg-white/20 backdrop-blur text-white text-xs font-bold px-3 py-1.5 rounded-full">
                        {{ $blog->category }}
                    </span>
                @endif
            </div>
        @endif

        <div class="px-5 sm:px-10 py-6 sm:py-8">
            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-[color:var(--grey-text)] mb-4">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-clock-history text-[var(--primary)]"></i>
                    {{ $blog->formatted_date }}
                </span>
                @if($blog->category)
                    <span class="flex items-center gap-1.5">
                        <i class="bi bi-tag text-[var(--primary)]"></i>
                        {{ $blog->category }}
                    </span>
                @endif
            </div>

            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[color:var(--text-color)] leading-tight mb-4">
                {{ $blog->title }}
            </h1>

            @if($blog->excerpt)
                <p class="text-sm sm:text-base text-[color:var(--grey-text)] italic border-l-4 border-[var(--primary)] pl-4 mb-6">
                    {{ $blog->excerpt }}
                </p>
            @endif

            {{-- Content --}}
            <div class="prose prose-orange max-w-none text-[color:var(--text-color)] leading-relaxed text-sm sm:text-base">
                {{ $blog->content }}
            </div>

            {{-- Share --}}
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <span class="text-sm font-semibold text-[color:var(--text-color)]">{{ __('Paylaş:') }}</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                   class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank"
                   class="w-9 h-9 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center hover:bg-sky-500 hover:text-white transition">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank"
                   class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank"
                   class="w-9 h-9 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center hover:bg-blue-700 hover:text-white transition">
                    <i class="bi bi-linkedin"></i>
                </a>
            </div>
        </div>
    </article>

    {{-- ==================== BACK TO BLOG ==================== --}}
    <div class="mt-8">
        <a href="/blog" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--primary)] hover:underline">
            <i class="bi bi-arrow-left"></i>
            {{ __('Bütün bloqlar') }}
        </a>
    </div>

    {{-- ==================== RELATED POSTS ==================== --}}
    @if($related->isNotEmpty())
        <div class="mt-10 sm:mt-14">
            <div class="flex items-center gap-3 mb-5">
                <h2 class="text-lg sm:text-xl font-bold text-[color:var(--text-color)]">{{ __('Oxşar Məqalələr') }}</h2>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($related as $item)
                    @include('components.cards.blog', [
                        'slug' => $item->slug,
                        'images' => $item->cover_image ? [$item->cover_image] : [],
                        'category' => (object) ['name' => $item->category ?? 'Bloq'],
                        'date' => $item->formatted_date ?? '',
                        'name' => $item->title ?? '',
                    ])
                @endforeach
            </div>
        </div>
    @endif
</div>

@include('components.scroll-top')
@endsection
