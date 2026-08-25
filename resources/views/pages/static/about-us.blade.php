@extends('layouts.app')

@section('title', __('about.page_title') . ' - Metraj.az')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about-us.css') }}">
@endpush

@section('content')
<main class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- Hero Banner --}}
    <section class="mt-4 sm:mt-6 bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl p-8 sm:p-14 text-white shadow-lg text-center relative overflow-hidden">
        <div class="max-w-3xl mx-auto relative z-10 space-y-3">
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                {{ __('about.hero_title') }}
            </h1>
            <p class="text-sm sm:text-base text-orange-100 leading-relaxed max-w-2xl mx-auto">
                {{ __('about.hero_subtitle') }}
            </p>
        </div>
    </section>

    {{-- Who We Are & Statistics --}}
    <section class="max-w-7xl mx-auto py-12 sm:py-16 px-4">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="w-full lg:w-1/2 space-y-5">
                <span class="bg-orange-50 text-[var(--primary)] text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider inline-block">
                    {{ __('about.page_title') }}
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                    {{ __('about.who_we_are_title') }}
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    {{ __('about.who_we_are_desc') }}
                </p>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    {{ __('about.mission_desc') }}
                </p>
                <div class="pt-2">
                    <a href="{{ route('agencies.list') }}"
                       class="inline-flex items-center gap-2 bg-[var(--primary)] text-white font-semibold px-7 py-3.5 rounded-2xl hover:bg-orange-600 transition shadow-md">
                        {{ __('about.meet_team_btn') }}
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Stats Counters --}}
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-gray-100">
                    <div class="space-y-1">
                        <span class="text-2xl sm:text-3xl font-black text-gray-900">900+</span>
                        <p class="text-xs text-gray-500 font-medium">{{ __('about.stats_homes_for_sale') }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-2xl sm:text-3xl font-black text-gray-900">280+</span>
                        <p class="text-xs text-gray-500 font-medium">{{ __('about.stats_agents') }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-2xl sm:text-3xl font-black text-gray-900">3,600+</span>
                        <p class="text-xs text-gray-500 font-medium">{{ __('about.stats_properties_sold') }}</p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative flex justify-center items-center">
                <div class="w-full max-w-md aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-orange-50">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80" alt="Metraj Team"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- 3 Step Process --}}
    <section class="bg-gray-50/80 rounded-3xl py-14 px-6 sm:px-10 border border-gray-100 my-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('about.process_title') }}</h2>
                <p class="text-sm text-gray-500">{{ __('about.process_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                {{-- Step 1 --}}
                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100/80 space-y-4 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-orange-50 text-[var(--primary)] rounded-2xl flex items-center justify-center text-2xl font-black mx-auto">
                        01
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('about.step_1_title') }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        {{ __('about.step_1_desc') }}
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100/80 space-y-4 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-orange-50 text-[var(--primary)] rounded-2xl flex items-center justify-center text-2xl font-black mx-auto">
                        02
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('about.step_2_title') }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        {{ __('about.step_2_desc') }}
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-100/80 space-y-4 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-orange-50 text-[var(--primary)] rounded-2xl flex items-center justify-center text-2xl font-black mx-auto">
                        03
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('about.step_3_title') }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        {{ __('about.step_3_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Loan Calculator Section --}}
    <section class="max-w-7xl mx-auto my-12 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10">
        @include('components.do-you-need-loan')
    </section>

    {{-- CTA Find Agent --}}
    <section class="max-w-7xl mx-auto mt-10 bg-orange-500 rounded-3xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="max-w-xl space-y-2">
            <h2 class="text-xl sm:text-3xl font-black">{{ __('about.find_agent_title') }}</h2>
            <p class="text-xs sm:text-sm text-orange-100 leading-relaxed">{{ __('about.find_agent_desc') }}</p>
        </div>
        <a href="{{ route('agencies.list') }}"
           class="bg-white text-orange-600 px-8 py-3.5 rounded-2xl font-bold text-sm hover:bg-orange-50 transition shadow-md whitespace-nowrap shrink-0">
            {{ __('about.browse_agencies_btn') }}
        </a>
    </section>
</main>
@endsection
