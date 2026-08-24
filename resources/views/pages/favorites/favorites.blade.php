@extends('layouts.app')

@section('title', __('Seçilmiş Elanlar') . ' - Metraj.az')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(isset($breadcrumbs))
        <div class="mb-6">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 shadow-sm">
                <i class="fa-solid fa-heart text-2xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ __('Seçilmiş Elanlar') }}</h1>
                    <span id="favsTotalBadge" class="{{ count($properties) > 0 ? '' : 'hidden' }} px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-600">
                        {{ count($properties) }}
                    </span>
                </div>
            </div>
        </div>

        <div id="favsActions" class="{{ count($properties) > 0 ? '' : 'hidden' }} flex items-center gap-3">
            <button id="clearAllFavoritesBtn" type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl transition duration-200 border border-rose-200 cursor-pointer">
                <i class="fa-regular fa-trash-can text-sm"></i>
                <span>{{ __('Hamısını Təmizlə') }}</span>
            </button>
            <a href="{{ route('listing') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition duration-200">
                <i class="bi bi-arrow-left"></i>
                <span>{{ __('Elanlara Qayıt') }}</span>
            </a>
        </div>
    </div>

    <!-- Empty State -->
    <div id="favsEmptyState" class="{{ count($properties) === 0 ? '' : 'hidden' }} text-center py-16 px-4 bg-white rounded-3xl border border-gray-100 shadow-sm max-w-lg mx-auto">
        <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
            <i class="fa-regular fa-heart"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Seçilmiş elanınız yoxdur') }}</h3>
        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto leading-relaxed">
            {{ __('Bəyəndiyiniz elanların üzərindəki ürək ikonuna klikləyərək onları seçilmişlər siyahısına əlavə edə bilərsiniz.') }}
        </p>
        <a href="{{ route('listing') }}" class="inline-flex items-center px-6 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl shadow-md transition-all duration-200 hover:shadow-lg">
            <i class="bi bi-search mr-2"></i>
            <span>{{ __('Elanları Kəşf Et') }}</span>
        </a>
    </div>

    <!-- Favorites Cards Grid -->
    <div id="favoritesContainer" class="{{ count($properties) > 0 ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @include('pages.favorites.partials.cards', ['properties' => $properties])
    </div>

</div>

@push('scripts')
    <script src="{{ asset('js/pages/favorites/favorites.js') }}"></script>
@endpush
@endsection
