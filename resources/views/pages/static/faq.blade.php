@extends('layouts.app')

@section('title', __('faq.page_title') . ' - KibrisKare.com')

@section('content')
@php
    $siteSetting = $siteSetting ?? \App\Modules\Shared\Models\SiteSetting::current();
@endphp
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- ==================== HERO / HEADER SECTION ==================== --}}
    <section class="mt-4 sm:mt-6 bg-white rounded-3xl p-6 sm:p-10 border border-gray-100/90 shadow-sm text-center">
        <div class="max-w-2xl mx-auto space-y-3">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                {{ __('faq.header_title') }}
            </h1>

            {{-- Live Search Box --}}
            <div class="pt-3 max-w-lg mx-auto">
                <div class="relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="faqSearchInput" placeholder="{{ __('faq.search_placeholder') }}"
                           class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition shadow-inner">
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== MAIN CONTENT SECTION ==================== --}}
    <section class="mt-6 sm:mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

            {{-- Left Side: Categories & Accordions (8 cols) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Category Pills --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-orange-500 text-white shadow-sm" data-category="all">
                        {{ __('faq.category_all') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="general">
                        {{ __('faq.category_general') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="listings">
                        {{ __('faq.category_listings') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="payments">
                        {{ __('faq.category_payments') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="safety">
                        {{ __('faq.category_safety') }}
                    </button>
                </div>

                {{-- Accordion List Container --}}
                <div id="faqAccordionContainer" class="space-y-4">

                    {{-- Category 1: Ümumi --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q1') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a1') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q2') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a2') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q3') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a3') }}
                        </div>
                    </div>

                    {{-- Category 2: Elan Yerləşdirmə --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q4') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a4') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q5') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a5') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q6') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a6') }}
                        </div>
                    </div>

                    {{-- Category 3: VIP & Ödənişlər --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q7') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a7') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q8') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a8') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q9') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a9') }}
                        </div>
                    </div>

                    {{-- Category 4: Təhlükəsizlik --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="safety">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q10') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a10') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="safety">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition cursor-pointer select-none">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('faq.q11') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('faq.a11') }}
                        </div>
                    </div>

                    {{-- No Search Results placeholder --}}
                    <div id="noFaqResults" class="hidden bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto text-2xl mb-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4 class="font-semibold text-base text-gray-900">{{ __('faq.no_results_title') }}</h4>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                            {{ __('faq.no_results_desc') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Right Side: Quick Contact Sidebar (4 cols) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Contact Support Card --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100/90 shadow-sm space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base leading-tight">{{ __('faq.need_help_title') }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ __('faq.contact_us_sub') }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">
                        {{ __('faq.need_help_desc') }}
                    </p>

                    <div class="space-y-2.5 pt-2 border-t border-gray-100 text-xs">
                        @if($siteSetting?->phone)
                            <a href="tel:{{ preg_replace('/[^\d\+]/', '', $siteSetting->phone) }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-orange-50/60 transition group">
                                <i class="bi bi-telephone-fill text-orange-500 text-sm"></i>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[11px] text-gray-400">{{ __('faq.hotline') }}</span>
                                    <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition">{{ $siteSetting->phone }}</span>
                                </div>
                            </a>
                        @endif

                        @php
                            $waClean = preg_replace('/[^\d]/', '', $siteSetting?->whatsapp ?: '905488888888');
                        @endphp
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-emerald-50/60 transition group">
                            <i class="bi bi-whatsapp text-emerald-600 text-sm"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-[11px] text-gray-400">WhatsApp</span>
                                <span class="font-semibold text-gray-900 group-hover:text-emerald-600 transition">{{ $siteSetting?->whatsapp ?: '+90 548 888 88 88' }}</span>
                            </div>
                        </a>

                        <a href="mailto:{{ $siteSetting?->email ?: 'info@kibriskare.com' }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-orange-50/60 transition group">
                            <i class="bi bi-envelope-fill text-orange-500 text-sm"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-[11px] text-gray-400">Email</span>
                                <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition">{{ $siteSetting?->email ?: 'info@kibriskare.com' }}</span>
                            </div>
                        </a>
                    </div>

                    <a href="{{ route('contact') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow transition duration-200 text-xs">
                        <span>{{ __('faq.go_to_contact_page') }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Fast Add Listing Promo --}}
                <div class="bg-gray-900 rounded-3xl p-6 sm:p-7 text-white shadow-sm space-y-4">
                    <span class="inline-block bg-orange-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded-md">
                        {{ __('faq.free_badge') }}
                    </span>
                    <h3 class="text-base sm:text-lg font-semibold leading-snug">
                        {{ __('faq.promo_title') }}
                    </h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        {{ __('faq.promo_desc') }}
                    </p>
                    <a href="{{ route('add-property') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-900 hover:bg-orange-500 hover:text-white font-semibold text-xs rounded-xl shadow transition duration-200">
                        <span>{{ __('faq.add_property_btn') }}</span>
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>

            </div>

        </div>
    </section>
</div>

@push('scripts')
    <script src="{{ asset('js/pages/static/faq.js') }}?v=2"></script>
@endpush
@endsection
