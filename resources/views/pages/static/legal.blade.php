@extends('layouts.app')

@section('title', $title . ' - KibrisKare.com')

@section('content')
<main class="w-full pb-20 bg-gray-50/50 min-h-screen">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    <section class="max-w-7xl mx-auto py-8 sm:py-12 px-4">
        {{-- Header --}}
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xs border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="bg-orange-50 text-orange-600 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider inline-flex items-center gap-1.5 mb-3">
                        <i class="fa-solid fa-scale-balanced"></i>
                        {{ __('footer.quick_links') }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ $title }}
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-2 flex items-center gap-2">
                        <i class="fa-regular fa-clock text-gray-400"></i>
                        <span>{{ __('contact.support_desc') ?? 'KibrisKare.com' }} &bull; {{ date('Y') }}</span>
                    </p>
                </div>

                {{-- Legal Tabs / Switcher --}}
                <div class="flex flex-wrap sm:flex-nowrap gap-2 bg-gray-100/80 p-1.5 rounded-2xl border border-gray-200/60 max-w-max">
                    <a href="{{ route('user-agreement') }}" 
                       class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeDoc === 'user_agreement' ? 'bg-white text-orange-600 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        {{ __('footer.user_agreement') }}
                    </a>
                    <a href="{{ route('privacy-policy') }}" 
                       class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeDoc === 'privacy_policy' ? 'bg-white text-orange-600 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        {{ __('footer.privacy_policy') }}
                    </a>
                    <a href="{{ route('terms-of-use') }}" 
                       class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeDoc === 'terms_of_use' ? 'bg-white text-orange-600 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        {{ __('footer.terms_of_use') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Legal Content Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-12 shadow-xs border border-gray-100">
            @if(!empty(trim(strip_tags($content))))
                <div class="prose prose-sm sm:prose-base max-w-none text-gray-700 leading-relaxed space-y-4 prose-headings:text-gray-900 prose-headings:font-bold prose-a:text-orange-600 prose-a:underline hover:prose-a:text-orange-700 prose-strong:text-gray-900 prose-ul:list-disc prose-ol:list-decimal">
                    {!! $content !!}
                </div>
            @else
                {{-- Default Structured Legal Content if Admin hasn't filled custom text yet --}}
                <div class="space-y-8 text-gray-700 leading-relaxed text-sm sm:text-base">
                    @if($activeDoc === 'user_agreement')
                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.user_agreement.s1_title') }}
                            </h2>
                            <p>{!! __('legal.user_agreement.s1_p1') !!}</p>
                            <p>{!! __('legal.user_agreement.s1_p2') !!}</p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.user_agreement.s2_title') }}
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li>{!! __('legal.user_agreement.s2_li1') !!}</li>
                                <li>{!! __('legal.user_agreement.s2_li2') !!}</li>
                                <li>{!! __('legal.user_agreement.s2_li3') !!}</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.user_agreement.s3_title') }}
                            </h2>
                            <p>{!! __('legal.user_agreement.s3_p1') !!}</p>
                        </div>
                    @elseif($activeDoc === 'privacy_policy')
                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.privacy_policy.s1_title') }}
                            </h2>
                            <p>{!! __('legal.privacy_policy.s1_p1') !!}</p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.privacy_policy.s2_title') }}
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li>{!! __('legal.privacy_policy.s2_li1') !!}</li>
                                <li>{!! __('legal.privacy_policy.s2_li2') !!}</li>
                                <li>{!! __('legal.privacy_policy.s2_li3') !!}</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.privacy_policy.s3_title') }}
                            </h2>
                            <p>{!! __('legal.privacy_policy.s3_p1') !!}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.terms_of_use.s1_title') }}
                            </h2>
                            <p>{!! __('legal.terms_of_use.s1_p1') !!}</p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.terms_of_use.s2_title') }}
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li>{!! __('legal.terms_of_use.s2_li1') !!}</li>
                                <li>{!! __('legal.terms_of_use.s2_li2') !!}</li>
                                <li>{!! __('legal.terms_of_use.s2_li3') !!}</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                {{ __('legal.terms_of_use.s3_title') }}
                            </h2>
                            <p>{!! __('legal.terms_of_use.s3_p1') !!}</p>
                        </div>
                    @endif
            @endif

            {{-- Support & Contact Footer inside legal card --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-orange-500 text-base"></i>
                    <span>{{ __('footer.all_rights_reserved') }} &bull; KibrisKare.com</span>
                </div>
                <div>
                    <a href="{{ route('contact') }}" class="text-orange-600 hover:text-orange-700 font-semibold underline">
                        {{ __('footer.contact_support') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
