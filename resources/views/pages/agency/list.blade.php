@extends('layouts.app')

@section('title', __('agency.page_title') . ' - Metraj.az')

@section('content')
<div class="w-full pt-4">
    @include('components.scroll-top')

    <section class="py-4 sm:py-6">
        {{-- Header + Search --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6 mb-6 sm:mb-8">
            {{-- Filter Tabs: Hamısı / Agentliklər / Müstəqil Rieltorlar --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/50 max-w-max shadow-sm flex-wrap" id="entityFilter" data-role="entity-filter">
                    <button type="button" data-filter="all"
                            class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-semibold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'all' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                        {{ __('agency.all') }}
                        <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5" id="countAll">{{ $agenciesCount + $agentsCount }}</span>
                    </button>
                    <button type="button" data-filter="agency"
                            class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-semibold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'agency' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                        <i class="fas fa-building mr-1"></i>{{ __('agency.agencies') }}
                        <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agenciesCount }}</span>
                    </button>
                    <button type="button" data-filter="agent"
                            class="filter-tab px-4 sm:px-5 py-2 rounded-xl font-semibold text-xs sm:text-sm tracking-wide transition duration-200 {{ ($activeType ?? 'all') === 'agent' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
                        <i class="fas fa-user-tie mr-1"></i>{{ __('agency.independent_agents') }}
                        <span class="ml-1 text-[10px] bg-gray-200/70 rounded-full px-1.5 py-0.5">{{ $agentsCount }}</span>
                    </button>
                </div>

                <div id="gridLoading" class="hidden text-orange-500 text-xs font-semibold flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin text-sm"></i> {{ __('agency.updating') }}
                </div>
            </div>

            <form id="agencyFilterForm" method="GET" action="{{ route('agencies.list') }}" class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                <input type="hidden" name="type" id="filterTypeInput" value="{{ $activeType ?? 'all' }}">
                <div class="relative flex-1 sm:w-72 lg:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" id="agencySearch" value="{{ $search ?? '' }}" placeholder="{{ __('agency.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition">
                </div>
                <button type="submit" class="bg-[var(--primary)] text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl hover:bg-orange-600 transition font-medium text-sm whitespace-nowrap">
                    {{ __('agency.search_btn') }}
                </button>
            </form>
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
    window.agencyListConfig = {
        url: "{{ route('agencies.list') }}"
    };
</script>
<script src="{{ asset('js/pages/agency/list.js') }}"></script>
@endpush
