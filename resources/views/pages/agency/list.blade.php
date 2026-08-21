@extends('layouts.app')

@section('content')
<div class="w-full pt-4">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    <section class="py-4 sm:py-6">
        {{-- Header + Search --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[color:var(--text-color)]">
                    {{ __('Agencies') }}
                </h1>
                <p class="text-sm sm:text-base text-[color:var(--grey-text)] mt-1 sm:mt-2">{{ __('Find the best real estate agencies') }}</p>
            </div>
            <form id="agencyFilterForm" method="GET" action="{{ route('agencies.list') }}" class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                <input type="hidden" name="type" id="filterTypeInput" value="{{ $activeType ?? 'all' }}">
                <div class="relative flex-1 sm:w-72 lg:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" id="agencySearch" value="{{ $search ?? '' }}" placeholder="{{ __('Search agencies...') }}"
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition">
                </div>
                <button type="submit" class="bg-[var(--primary)] text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl hover:bg-orange-600 transition font-medium text-sm whitespace-nowrap">
                    {{ __('Search') }}
                </button>
            </form>
        </div>

        {{-- Filter Tabs: Hamısı / Agentliklər / Müstəqil Rieltorlar --}}
        <div class="mb-6 sm:mb-8 flex items-center justify-between flex-wrap gap-4">
            <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/50 max-w-max shadow-sm flex-wrap" id="entityFilter" data-role="entity-filter">
                <button type="button" data-filter="all"
                        class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'all' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                    {{ __('Hamısı') }}
                    <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5" id="countAll">{{ $agenciesCount + $agentsCount }}</span>
                </button>
                <button type="button" data-filter="agency"
                        class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'agency' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                    <i class="fas fa-building mr-1"></i>{{ __('Agentliklər') }}
                    <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agenciesCount }}</span>
                </button>
                <button type="button" data-filter="agent"
                        class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'agent' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                    <i class="fas fa-user-tie mr-1"></i>{{ __('Müstəqil Rieltorlar') }}
                    <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agentsCount }}</span>
                </button>
            </div>

            <div id="gridLoading" class="hidden text-orange-500 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-spinner fa-spin text-sm"></i> {{ __('Yenilənir...') }}
            </div>
        </div>

        {{-- Unified Grid: Mixed Agencies & Realtors --}}
        <div id="entityGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
            @include('pages.agency.partials.grid', ['items' => $items])
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('agencyFilterForm');
    const searchInput = document.getElementById('agencySearch');
    const filterTypeInput = document.getElementById('filterTypeInput');
    const filterBtns = document.querySelectorAll('#entityFilter .filter-tab');
    const entityGrid = document.getElementById('entityGrid');
    const gridLoading = document.getElementById('gridLoading');

    let currentType = filterTypeInput ? filterTypeInput.value : 'all';
    let searchDebounceTimer = null;

    function fetchEntities() {
        const query = searchInput ? searchInput.value.trim() : '';
        const params = new URLSearchParams();
        if (currentType && currentType !== 'all') {
            params.set('type', currentType);
        }
        if (query) {
            params.set('search', query);
        }

        const queryString = params.toString();
        const requestUrl = '{{ route("agencies.list") }}' + (queryString ? '?' + queryString : '');

        // Update URL in address bar
        window.history.pushState({ type: currentType, search: query }, '', requestUrl);

        if (gridLoading) gridLoading.classList.remove('hidden');

        fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (entityGrid && data.html !== undefined) {
                entityGrid.innerHTML = data.html;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
        })
        .finally(() => {
            if (gridLoading) gridLoading.classList.add('hidden');
        });
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
                b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            });
            this.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            this.classList.add('bg-white', 'text-orange-500', 'shadow-sm');

            currentType = this.getAttribute('data-filter') || 'all';
            if (filterTypeInput) filterTypeInput.value = currentType;
            fetchEntities();
        });
    });

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(searchDebounceTimer);
            fetchEntities();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(fetchEntities, 350);
        });
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(e) {
        if (e.state) {
            currentType = e.state.type || 'all';
            if (filterTypeInput) filterTypeInput.value = currentType;
            if (searchInput) searchInput.value = e.state.search || '';

            filterBtns.forEach(function(b) {
                const f = b.getAttribute('data-filter');
                if (f === currentType) {
                    b.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                    b.classList.add('bg-white', 'text-orange-500', 'shadow-sm');
                } else {
                    b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                    b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                }
            });

            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (entityGrid && data.html !== undefined) {
                    entityGrid.innerHTML = data.html;
                }
            });
        }
    });
});
</script>
@endpush
