@extends('layouts.app')

@section('content')
@include('components.breadcrumb', ['items' => $breadcrumbs ?? []])

@include('components.scroll-top')

<header>
    <div class="container pt-20 mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-start gap-4 md:gap-6">
            <h2 class="text-2xl lg:text-4xl font-bold py-4 text-[color:var(--text-color)]">
                {{ __('Blog grid') }}
            </h2>

            <div class="flex flex-wrap items-center gap-2">

                <button id="gridViewBtn" class="px-3 grid-btn py-2 rounded-md active-filter" data-view="grid">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>

                <button id="listViewBtn" class="px-3 py-2 list border border-[var(--border-color)] rounded-md" data-view="list">
                    <i class="fas fa-list text-[color:var(--icon-grey)]"></i>
                </button>

            </div>
        </div>
    </div>
</header>
<main>
    <section id="blog-cards">
        <div class="container mx-auto px-3">
            <div class="blog-cards">

                @forelse($blogs as $blog)
                    @include('components.cards.blog', [
                        'slug' => $blog->slug,
                        'images' => $blog->cover_image ? [$blog->cover_image] : [],
                        'category' => (object) ['name' => $blog->category ?? 'Bloq'],
                        'date' => $blog->formatted_date ?? '',
                        'name' => $blog->title ?? '',
                    ])
                @empty
                    <div class="text-center py-16 sm:py-20 col-span-full">
                        <div class="text-5xl sm:text-6xl mb-4 text-gray-300"><i class="bi bi-journal-richtext"></i></div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-500">{{ __('Hələ heç bir bloq dərc edilməyib') }}</h3>
                        <p class="text-sm sm:text-base text-gray-400 mt-2">{{ __('Tezliklə yeni məqalələr yayımlanacaq') }}</p>
                    </div>
                @endforelse

            </div>

        </div>

    </section>
    <div class="mt-10">
        {{ $blogs->onEachSide(2)->links() }}
    </div>

</main>

@push('styles')
<link rel="stylesheet" href="/css/blog.css">
<link rel="stylesheet" href="/css/listing-details.css">
<link rel="stylesheet" href="/css/agencies.css">
<link rel="stylesheet" href="/css/app.css">
@endpush

@push('scripts')
<script src="/js/pages/blog/list.js"></script>
@endpush
@endsection
