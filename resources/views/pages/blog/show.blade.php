@extends('layouts.app')

@php
    $resolvedBlogTitle = ($blog->meta_title ?: $blog->title) . ' - KibrisKare.com';
    $resolvedBlogDescription = $blog->meta_description ?: ($blog->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 160));
    $coverImgUrl = $blog->cover_image ? (filter_var($blog->cover_image, FILTER_VALIDATE_URL) ? $blog->cover_image : asset('storage/' . ltrim($blog->cover_image, '/'))) : null;
@endphp

@section('title', $resolvedBlogTitle)
@section('meta_description', $resolvedBlogDescription)
@section('h1', $blog->title)

@push('styles')
<style>
.blog-content {
    font-size: 1.0625rem;
    line-height: 1.85;
    color: #374151;
    word-break: break-word;
}
.blog-content h1,
.blog-content h2,
.blog-content h3,
.blog-content h4,
.blog-content h5,
.blog-content h6 {
    color: #111827 !important;
    font-weight: 700 !important;
    line-height: 1.35 !important;
    margin-top: 2.25rem !important;
    margin-bottom: 1rem !important;
}
.blog-content h1 { font-size: 1.875rem !important; }
.blog-content h2 { font-size: 1.5rem !important; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem; }
.blog-content h3 { font-size: 1.3rem !important; }
.blog-content h4 { font-size: 1.15rem !important; }
.blog-content h5 { font-size: 1.05rem !important; }
.blog-content h6 { font-size: 0.95rem !important; }

.blog-content p {
    margin-top: 0 !important;
    margin-bottom: 1.35rem !important;
    line-height: 1.85 !important;
    color: #374151 !important;
}

.blog-content strong,
.blog-content b {
    font-weight: 700 !important;
    color: #111827 !important;
}

.blog-content em,
.blog-content i {
    font-style: italic !important;
}

.blog-content ul {
    list-style-type: disc !important;
    padding-left: 1.75rem !important;
    margin-top: 0.75rem !important;
    margin-bottom: 1.5rem !important;
}

.blog-content ol {
    list-style-type: decimal !important;
    padding-left: 1.75rem !important;
    margin-top: 0.75rem !important;
    margin-bottom: 1.5rem !important;
}

.blog-content li {
    margin-bottom: 0.6rem !important;
    line-height: 1.75 !important;
    color: #374151 !important;
    display: list-item !important;
}

.blog-content li::marker {
    color: #f97316 !important;
}

.blog-content blockquote {
    border-left: 4px solid #f97316 !important;
    background-color: #fff7ed !important;
    padding: 1.25rem 1.5rem !important;
    margin: 2rem 0 !important;
    border-radius: 0 0.75rem 0.75rem 0 !important;
    color: #374151 !important;
    font-style: italic !important;
}

.blog-content blockquote p {
    margin-bottom: 0.5rem !important;
}

.blog-content blockquote p:last-child {
    margin-bottom: 0 !important;
}

.blog-content pre {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
    padding: 1rem 1.25rem !important;
    border-radius: 0.75rem !important;
    overflow-x: auto !important;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    font-size: 0.875rem !important;
    margin: 1.25rem 0 !important;
    line-height: 1.6 !important;
}

.blog-content code {
    background-color: #f3f4f6 !important;
    color: #ea580c !important;
    padding: 0.2rem 0.45rem !important;
    border-radius: 0.375rem !important;
    font-family: ui-monospace, monospace !important;
    font-size: 0.875em !important;
}

.blog-content pre code {
    background-color: transparent !important;
    color: inherit !important;
    padding: 0 !important;
}

.blog-content a {
    color: #ea580c !important;
    text-decoration: underline !important;
    text-underline-offset: 3px !important;
    font-weight: 600 !important;
    transition: color 0.15s ease-in-out !important;
}

.blog-content a:hover {
    color: #c2410c !important;
}

.blog-content img {
    max-width: 100% !important;
    height: auto !important;
    border-radius: 1rem !important;
    margin: 2rem auto !important;
    display: block !important;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
}

.blog-content table {
    width: 100% !important;
    border-collapse: collapse !important;
    margin: 1.75rem 0 !important;
    font-size: 0.95rem !important;
}

.blog-content th,
.blog-content td {
    border: 1px solid #e5e7eb !important;
    padding: 0.75rem 1rem !important;
    text-align: left !important;
}

.blog-content th {
    background-color: #f9fafb !important;
    font-weight: 700 !important;
    color: #111827 !important;
}

.blog-content hr {
    border: 0 !important;
    border-top: 1px solid #e5e7eb !important;
    margin: 2.25rem 0 !important;
}
</style>
@endpush

@section('content')
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])

    {{-- ==================== BLOG HEADER ==================== --}}
    <article class="bg-white rounded-3xl shadow-sm border border-gray-100 mt-4 sm:mt-6 overflow-hidden max-w-4xl mx-auto">
        @if($coverImgUrl)
            <div class="relative h-64 sm:h-80 lg:h-96 overflow-hidden bg-gray-50">
                <img src="{{ $coverImgUrl }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                @if($blog->category)
                    <span class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow-md">
                        {{ $blog->category }}
                    </span>
                @endif
            </div>
        @else
            <div class="relative h-40 sm:h-56 bg-gradient-to-r from-orange-500 to-amber-600 flex items-center justify-center">
                @if($blog->category)
                    <span class="bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        {{ $blog->category }}
                    </span>
                @endif
            </div>
        @endif

        <div class="px-6 sm:px-12 py-8 sm:py-10">
            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-gray-500 mb-4">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-orange-500"></i>
                    {{ $blog->formatted_date }}
                </span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-eye text-orange-500"></i>
                    {{ number_format($blog->views_count ?? 0) }} {{ __('blog.views_count') }}
                </span>
                @if($blog->category)
                    <span class="text-gray-300">•</span>
                    <span class="flex items-center gap-1.5 text-orange-600 font-medium">
                        <i class="bi bi-tag text-orange-500"></i>
                        {{ $blog->category }}
                    </span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-6">
                {{ $blog->title }}
            </h1>

            {{-- Content (Raw HTML from RichEditor) --}}
            <div class="blog-content text-gray-700 leading-relaxed">
                {!! $blog->content !!}
            </div>

            {{-- Share --}}
            <div class="flex items-center gap-3 mt-10 pt-6 border-t border-gray-100">
                <span class="text-sm font-semibold text-gray-900">{{ __('blog.share') }}:</span>
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

    {{-- ==================== RELATED POSTS ==================== --}}
    @if($related->isNotEmpty())
        <div class="mt-10 sm:mt-14">
            <div class="flex items-center gap-3 mb-5">
                <h2 class="text-lg sm:text-xl font-semibold text-[color:var(--text-color)]">{{ __('blog.related_posts') }}</h2>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($related as $item)
                    <x-cards.blog :blog="$item" />
                @endforeach
            </div>
        </div>
    @endif
</div>

@include('components.scroll-top')
@endsection
