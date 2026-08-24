@extends('layouts.app')

@section('title', __('Bloq və Xəbərlər') . ' - Metraj.az')

@section('content')
<div class="w-full pt-4 pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    <section class="py-4 sm:py-6">
        {{-- Header + Search & View Switcher --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[color:var(--text-color)] leading-tight">
                    {{ __('Bloq və Xəbərlər') }}
                </h1>
                <p class="text-sm sm:text-base text-[color:var(--grey-text)] mt-1">
                    {{ __('Daşınmaz əmlak bazarı, faydalı məsləhətlər və ən son yeniliklər') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('blog.list') }}" class="relative flex-1 sm:w-72 lg:w-80">
                    <input type="hidden" name="category" value="{{ $category ?? 'all' }}">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('Məqalə axtar...') }}"
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 shadow-sm transition">
                </form>

                {{-- Grid / List View Toggle --}}
                <div class="flex items-center bg-gray-100 p-1 rounded-2xl border border-gray-200/50 shadow-sm">
                    <button type="button" id="gridViewBtn" title="Qrid görünüşü"
                            class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 bg-white text-orange-500 shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill text-base"></i>
                    </button>
                    <button type="button" id="listViewBtn" title="Siyahı görünüşü"
                            class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 text-gray-600 hover:text-gray-900 hover:bg-white/50">
                        <i class="bi bi-list-ul text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Categories Pills --}}
        @if(!empty($categories) && $categories->isNotEmpty())
        <div class="mb-6 sm:mb-8 overflow-x-auto pb-2 -mx-2 px-2 scrollbar-none">
            <div class="flex items-center gap-2 flex-nowrap">
                <a href="{{ route('blog.list', array_filter(['search' => $search, 'category' => 'all'])) }}"
                   class="px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition duration-200 whitespace-nowrap {{ ($category ?? 'all') === 'all' ? 'bg-orange-500 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                    {{ __('Hamısı') }}
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.list', array_filter(['search' => $search, 'category' => $cat])) }}"
                       class="px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition duration-200 whitespace-nowrap {{ ($category ?? '') === $cat ? 'bg-orange-500 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Blog Grid / List Container --}}
        @if($blogs->isEmpty())
            <div class="bg-white rounded-3xl p-12 sm:p-16 text-center border border-gray-100 shadow-sm col-span-full">
                <div class="w-20 h-20 bg-orange-50 text-[var(--primary)] rounded-full flex items-center justify-center mx-auto text-3xl mb-4">
                    <i class="bi bi-journal-richtext"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ __('Məqalə tapılmadı') }}</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5 max-w-sm mx-auto">
                    {{ __('Axtarışınıza uyğun heç bir bloq məqaləsi tapılmadı. Zəhmət olmasa axtarış sözünü dəyişin.') }}
                </p>
                <a href="{{ route('blog.list') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    <i class="bi bi-arrow-left"></i> {{ __('Bütün məqalələrə qayıt') }}
                </a>
            </div>
        @else
            <div id="blogContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach($blogs as $blog)
                    <x-cards.blog :blog="$blog" />
                @endforeach
            </div>

            @if($blogs->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $blogs->links() }}
                </div>
            @endif
        @endif
    </section>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/blog/list.js') }}"></script>
@endpush
