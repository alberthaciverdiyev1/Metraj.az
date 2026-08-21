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
                    <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-72 lg:w-80">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="agencySearch" placeholder="{{ __('Search agencies...') }}"
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition">
                        </div>
                        <button class="bg-[var(--primary)] text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl hover:bg-orange-600 transition font-medium text-sm whitespace-nowrap">
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>

                @if($agencies->isEmpty() && (!empty($independentAgents) && $independentAgents->isEmpty()))
                    <div class="text-center py-16 sm:py-20">
                        <div class="text-5xl sm:text-6xl mb-4 text-gray-300"><i class="fas fa-building"></i></div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-500">{{ __('No agencies found') }}</h3>
                        <p class="text-sm sm:text-base text-gray-400 mt-2">{{ __('Check back later for new agencies') }}</p>
                    </div>
                @else
                    {{-- Filter Tabs: Hamısı / Agentliklər / Müstəqil Rieltorlar --}}
                    <div class="mb-6 sm:mb-8">
                        <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/50 max-w-max shadow-sm flex-wrap" id="entityFilter" data-role="entity-filter">
                            <button type="button" data-filter="all"
                                    class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 bg-white text-orange-500 shadow-sm">
                                {{ __('Hamısı') }}
                                <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agencies->count() + ($independentAgents?->count() ?? 0) }}</span>
                            </button>
                            <button type="button" data-filter="agency"
                                    class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 text-gray-600 hover:text-gray-900 hover:bg-white/50">
                                <i class="fas fa-building mr-1"></i>{{ __('Agentliklər') }}
                                <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agencies->count() }}</span>
                            </button>
                            <button type="button" data-filter="agent"
                                    class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition duration-200 text-gray-600 hover:text-gray-900 hover:bg-white/50">
                                <i class="fas fa-user-tie mr-1"></i>{{ __('Müstəqil Rieltorlar') }}
                                <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $independentAgents?->count() ?? 0 }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Unified Grid: agencies + independent realtors --}}
                    <div id="entityGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                        @foreach($agencies as $agency)
                        <div data-entity-card="true" data-type="agency" onclick="window.location.href='/agency/{{ $agency->id }}'"
                             class="cursor-pointer bg-white rounded-2xl overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-xl border border-gray-100">

                            <div class="relative overflow-hidden aspect-[16/9] sm:aspect-[5/3]">
                                <img src="{{ $agency->banner ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80' }}"
                                     alt="{{ $agency->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>

                            <div class="flex px-4 sm:px-5 -mt-8 sm:-mt-10 relative z-10">
                                @if($agency->logo)
                                    <img class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-4 border-white shadow-md object-cover bg-white"
                                         src="{{ asset('storage/'.$agency->logo) }}" alt="{{ $agency->name }}">
                                @else
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-4 border-white shadow-md bg-[var(--primary)] text-white flex items-center justify-center text-xl font-black">
                                        {{ strtoupper(substr($agency->name ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-2 sm:pt-3 flex flex-col flex-1">
                                <h3 class="font-bold text-[color:var(--text-color)] text-sm sm:text-base lg:text-lg leading-snug hover:text-[color:var(--primary)] transition-colors line-clamp-1">
                                    {{ $agency->name }}
                                </h3>

                                @if($agency->address)
                                <div class="flex items-center gap-1.5 text-xs sm:text-sm text-[color:var(--grey-text)] mt-1 sm:mt-1.5">
                                    <i class="fas fa-map-pin text-xs flex-shrink-0"></i>
                                    <span class="line-clamp-1">{{ $agency->address }}</span>
                                </div>
                                @endif

                                <div class="flex items-center gap-2 sm:gap-4 mt-2 sm:mt-3 text-xs sm:text-sm text-[color:var(--grey-text)]">
                                    <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                                        <i class="fas fa-home text-[color:var(--primary)]"></i>
                                        {{ $agency->properties_count ?? 0 }} {{ __('elan') }}
                                    </span>
                                    @if($agency->phone)
                                    <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                                        <i class="fas fa-phone text-green-500"></i>
                                        {{ $agency->phone }}
                                    </span>
                                    @endif
                                </div>

                                <div class="mt-auto pt-3 sm:pt-4">
                                    <a href="/agency/{{ $agency->id }}" onclick="event.stopPropagation()"
                                       class="block w-full text-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[color:var(--primary)] text-[color:var(--primary)] text-xs sm:text-sm font-medium hover:bg-[color:var(--primary)] hover:text-white transition-all">
                                        {{ __('View Profile') }}
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @foreach($independentAgents ?? [] as $agent)
                        <div data-entity-card="true" data-type="agent" onclick="window.location.href='/agent/{{ $agent->id }}'"
                             class="cursor-pointer bg-white rounded-2xl overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-xl border border-gray-100">

                            <div class="relative overflow-hidden aspect-[16/9] sm:aspect-[5/3] bg-gradient-to-br from-orange-100 via-orange-50 to-gray-50">
                                @if($agent->avatar)
                                    <img src="{{ $agent->avatar }}" alt="{{ $agent->user?->name }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="bi bi-person-badge text-5xl text-[var(--primary)]/30"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex px-4 sm:px-5 -mt-8 sm:-mt-10 relative z-10">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-4 border-white shadow-md overflow-hidden bg-orange-500 text-white flex items-center justify-center text-xl font-black">
                                    @if($agent->avatar)
                                        <img src="{{ $agent->avatar }}" alt="{{ $agent->user?->name }}" class="w-full h-full object-cover" />
                                    @else
                                        {{ strtoupper(substr($agent->user?->name ?? 'R', 0, 1)) }}
                                    @endif
                                </div>
                            </div>

                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-2 sm:pt-3 flex flex-col flex-1">
                                <h3 class="font-bold text-[color:var(--text-color)] text-sm sm:text-base lg:text-lg leading-snug hover:text-[color:var(--primary)] transition-colors line-clamp-1">
                                    {{ $agent->user?->name ?? __('Rieltor') }}
                                </h3>
                                <p class="text-xs sm:text-sm text-[color:var(--grey-text)] mt-0.5">
                                    {{ $agent->position ?? __('Müstəqil Rieltor') }}
                                </p>

                                <div class="flex items-center gap-2 sm:gap-4 mt-2 sm:mt-3 text-xs sm:text-sm text-[color:var(--grey-text)]">
                                    <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                                        <i class="fas fa-home text-[color:var(--primary)]"></i>
                                        {{ $agent->published_properties_count ?? 0 }} {{ __('elan') }}
                                    </span>
                                    @if($agent->phone)
                                    <span class="flex items-center gap-1 sm:gap-1.5 whitespace-nowrap">
                                        <i class="fas fa-phone text-green-500"></i>
                                        {{ $agent->phone }}
                                    </span>
                                    @endif
                                </div>

                                <div class="mt-auto pt-3 sm:pt-4">
                                    <span class="block w-full text-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs sm:text-sm font-medium hover:from-orange-600 hover:to-orange-700 transition-all shadow-sm">
                                        {{ __('View Profile') }}
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="noResults" class="hidden text-center py-16 sm:py-20">
                        <div class="text-5xl mb-4 text-gray-300"><i class="fas fa-search"></i></div>
                        <h3 class="text-lg font-semibold text-gray-500">{{ __('Nəticə tapılmadı') }}</h3>
                        <p class="text-sm text-gray-400 mt-2">{{ __('Axtarış və ya filtr meyarlarını dəyişin') }}</p>
                    </div>
                @endif
            </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('agencySearch');
    const filterBtns = document.querySelectorAll('#entityFilter .filter-tab');
    const cards = document.querySelectorAll('[data-entity-card]');
    const noResults = document.getElementById('noResults');

    function applyFilters() {
        const activeBtn = document.querySelector('#entityFilter .filter-tab.bg-white') || filterBtns[0];
        const f = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';

        let visible = 0;
        cards.forEach(function(card) {
            const type = card.getAttribute('data-type');
            const showByType = f === 'all' || type === f;
            const text = card.textContent.toLowerCase();
            const showBySearch = !q || text.indexOf(q) !== -1;
            const show = showByType && showBySearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResults) noResults.classList.toggle('hidden', visible !== 0);
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
                b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            });
            this.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            this.classList.add('bg-white', 'text-orange-500', 'shadow-sm');
            applyFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('keyup', applyFilters);
    }

    applyFilters();
});
</script>
@endpush
