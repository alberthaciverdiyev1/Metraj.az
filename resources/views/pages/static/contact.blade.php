@extends('layouts.app')

@section('title', __('contact.page_title') . ' - KibrisKare.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
@php
    $siteSetting = $siteSetting ?? \App\Modules\Shared\Models\SiteSetting::current();
@endphp
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="py-4 sm:py-6">
        <div class="max-w-3xl mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[color:var(--text-color)] leading-tight">
                {{ __('contact.page_title') }}
            </h1>
            <p class="text-sm sm:text-base text-[color:var(--grey-text)] mt-2 leading-relaxed">
                {{ __('contact.page_desc') }}
            </p>
        </div>

        {{-- ==================== CONTACT INFO CARDS ==================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8 sm:mb-12">
            {{-- Phone --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[var(--primary)] flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-base mb-1">{{ __('contact.phone') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('contact.phone_desc') }}</p>
                @if($siteSetting?->phone)
                    <a href="tel:{{ preg_replace('/[^\d\+]/', '', $siteSetting->phone) }}" class="text-sm font-semibold text-[var(--primary)] hover:underline mt-auto">
                        {{ $siteSetting->phone }}
                    </a>
                @endif
                @if($siteSetting?->phone_secondary)
                    <a href="tel:{{ preg_replace('/[^\d\+]/', '', $siteSetting->phone_secondary) }}" class="text-xs text-gray-600 hover:text-[var(--primary)] mt-1">
                        {{ $siteSetting->phone_secondary }}
                    </a>
                @endif
            </div>

            {{-- WhatsApp --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 shrink-0">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-base mb-1">{{ __('contact.whatsapp_title') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('contact.whatsapp_desc') }}</p>
                @php
                    $waClean = preg_replace('/[^\d]/', '', $siteSetting?->whatsapp ?: '905488888888');
                @endphp
                <a href="https://wa.me/{{ $waClean }}" target="_blank" class="text-sm font-semibold text-emerald-600 hover:underline mt-auto flex items-center gap-1.5">
                    {{ __('contact.start_chat') }} <i class="bi bi-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Email --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-base mb-1">{{ __('contact.email') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('contact.email_desc') }}</p>
                <a href="mailto:{{ $siteSetting?->email ?: 'info@kibriskare.com' }}" class="text-sm font-semibold text-blue-600 hover:underline mt-auto break-all">
                    {{ $siteSetting?->email ?: 'info@kibriskare.com' }}
                </a>
                @if($siteSetting?->support_email && $siteSetting->support_email !== $siteSetting->email)
                    <a href="mailto:{{ $siteSetting->support_email }}" class="text-xs text-gray-500 hover:text-blue-600 mt-1 break-all">
                        {{ $siteSetting->support_email }}
                    </a>
                @endif
            </div>

            {{-- Address --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3 class="font-semibold text-gray-900 text-base mb-1">{{ __('contact.head_office') }}</h3>
                <p class="text-xs text-gray-500 mb-2">{{ __('contact.office_location_desc') }}</p>
                <span class="text-xs text-gray-700 leading-relaxed mt-auto">
                    {{ $siteSetting?->getTrans('address') ?: __('contact.office_address') }}
                </span>
            </div>
        </div>

        {{-- ==================== MAIN 2-COLUMN SECTION: FORM + MAP ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            {{-- Left: Contact Form (7 cols) --}}
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 lg:p-10 border border-gray-100/90 shadow-sm">
                <div class="mb-6 sm:mb-8">
                    <span class="bg-orange-50 text-[var(--primary)] text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-2">
                        {{ __('contact.write_to_us') }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-semibold text-[color:var(--text-color)]">
                        {{ __('contact.inquiry_form_title') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-[color:var(--grey-text)] mt-1">
                        {{ __('contact.inquiry_form_desc') }}
                    </p>
                </div>

                <form action="{{ route('inquiries.contact') }}" method="POST" id="contactForm" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label for="contact_name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('contact.full_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact_name" name="name" required placeholder="{{ __('contact.name_placeholder') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="contact_phone" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('contact.contact_phone') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_phone" name="phone" required placeholder="{{ __('+90 548 000 00 00') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label for="contact_email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('contact.email_address') }}
                            </label>
                            <input type="email" id="contact_email" name="email" placeholder="{{ __('email@example.com') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>

                        {{-- Service Interest --}}
                        <div>
                            <label for="contact_subject" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('contact.subject_service_type') }}
                            </label>
                            <select id="contact_subject" name="interest"
                                    class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition cursor-pointer">
                                <option value="">{{ __('contact.select') }}</option>
                                <option value="buy">{{ __('contact.property_buy') }}</option>
                                <option value="sell">{{ __('contact.property_sell') }}</option>
                                <option value="rent">{{ __('contact.property_rent') }}</option>
                                <option value="agency">{{ __('contact.agency_partnership') }}</option>
                                <option value="other">{{ __('contact.other_matters') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="contact_message" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('contact.your_message') }}
                        </label>
                        <textarea id="contact_message" name="message" rows="4" placeholder="{{ __('contact.message_placeholder') }}"
                                  class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-between flex-wrap gap-4">
                        <button type="submit" id="contactSubmitBtn"
                                class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-2xl shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                            <span>{{ __('contact.send_message') }}</span>
                            <i class="bi bi-send-fill text-xs"></i>
                        </button>
                        <span class="text-[11px] text-gray-400">
                            <i class="bi bi-shield-check text-green-500 mr-1"></i>{{ __('contact.privacy_guaranteed') }}
                        </span>
                    </div>
                </form>
            </div>

            {{-- Right: Interactive Map & Working Hours (5 cols) --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Map Card --}}
                <div class="bg-white rounded-3xl overflow-hidden border border-gray-100/90 shadow-sm p-2">
                    <div id="contactMap" class="w-full h-[260px] sm:h-[300px] rounded-2xl z-0"></div>
                </div>

                {{-- Working Hours Card --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100/90 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[var(--primary)] flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">{{ __('contact.working_hours') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('contact.customer_service') }}</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs sm:text-sm text-gray-600">
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                            <span class="font-medium text-gray-800">{{ __('contact.mon_fri') }}</span>
                            <span class="font-semibold text-gray-900">{{ $siteSetting?->working_hours_mon_fri ?: '09:00 – 19:00' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                            <span class="font-medium text-gray-800">{{ __('contact.saturday') }}</span>
                            <span class="font-semibold text-gray-900">{{ $siteSetting?->working_hours_sat ?: '10:00 – 18:00' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 text-gray-500">
                            <span class="font-medium">{{ __('contact.sunday') }}</span>
                            <span class="text-orange-500 font-semibold">{{ $siteSetting?->working_hours_sun ?: __('contact.online_24_7') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== FAQ BANNER CTA ==================== --}}
        <div class="mt-10 sm:mt-12 bg-orange-500 rounded-3xl p-8 sm:p-10 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="text-xs font-semibold uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block mb-2 backdrop-blur-sm">
                    {{ __('contact.need_help') }}
                </span>
                <h3 class="text-xl sm:text-2xl font-black">{{ __('contact.have_faq_questions') }}</h3>
                <p class="text-xs sm:text-sm text-orange-100 mt-1.5 leading-relaxed">
                    {{ __('contact.faq_banner_desc') }}
                </p>
            </div>
            <a href="{{ route('faq') }}"
               class="px-6 sm:px-8 py-3.5 bg-white text-orange-600 hover:bg-orange-50 font-bold text-sm rounded-2xl shadow-md transition duration-200 whitespace-nowrap flex items-center gap-2 shrink-0">
                <span>{{ __('contact.go_to_faq') }}</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
    window.contactConfig = {
        lat: {{ $siteSetting?->map_latitude ?: 35.3382440 }},
        lng: {{ $siteSetting?->map_longitude ?: 33.3186270 }},
        i18n: {
            sending: "{{ __('contact.sending') }}",
            sent: "{{ __('contact.message_sent_success') }}",
            error: "{{ __('contact.error_occurred') }}",
            network: "{{ __('contact.network_error') }}",
            send: "{{ __('contact.send_message') }}",
            mapPopupTitle: "{{ $siteSetting?->copyright_text ?: 'KibrisKare.com' }}",
            mapPopupAddress: "{{ addslashes($siteSetting?->getTrans('address') ?: __('contact.office_address')) }}"
        }
    };
</script>
<script src="{{ asset('js/pages/static/contact.js') }}"></script>
@endpush
