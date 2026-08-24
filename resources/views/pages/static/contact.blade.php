@extends('layouts.app')

@section('title', __('Bizimlə Əlaqə') . ' - Metraj.az')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container {
        font-family: inherit;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="py-4 sm:py-6">
        <div class="max-w-3xl mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[color:var(--text-color)] leading-tight">
                {{ __('Bizimlə Əlaqə') }}
            </h1>
            <p class="text-sm sm:text-base text-[color:var(--grey-text)] mt-2 leading-relaxed">
                {{ __('Daşınmaz əmlak alqı-satqısı, kirayəsi, qiymətləndirmə və ya əməkdaşlıqla bağlı suallarınız üçün bizə müraciət edin. Peşəkar heyətimiz sizə yardım etməyə hazırdır.') }}
            </p>
        </div>

        {{-- ==================== CONTACT INFO CARDS ==================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8 sm:mb-12">
            {{-- Phone --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[var(--primary)] flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1">{{ __('Telefon') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('İş saatlarında zəng edin') }}</p>
                <a href="tel:+994501234567" class="text-sm font-bold text-[var(--primary)] hover:underline mt-auto">
                    +994 50 123 45 67
                </a>
                <a href="tel:+994124000000" class="text-xs text-gray-600 hover:text-[var(--primary)] mt-1">
                    +994 12 400 00 00
                </a>
            </div>

            {{-- WhatsApp --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 shrink-0">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1">{{ __('WhatsApp') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('24/7 onlayn mesaj dəstəyi') }}</p>
                <a href="https://wa.me/994501234567" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline mt-auto flex items-center gap-1.5">
                    {{ __('Çata Başla') }} <i class="bi bi-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Email --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1">{{ __('E-poçt') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('Rəsmi müraciətlər üçün') }}</p>
                <a href="mailto:info@metraj.az" class="text-sm font-bold text-blue-600 hover:underline mt-auto break-all">
                    info@metraj.az
                </a>
            </div>

            {{-- Address --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4 shrink-0">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1">{{ __('Baş Ofis') }}</h3>
                <p class="text-xs text-gray-500 mb-2">{{ __('Bakı & Şimali Kipr') }}</p>
                <span class="text-xs text-gray-700 leading-relaxed mt-auto">
                    Bakı ş., Nəsimi r., Nizami küç. 45
                </span>
            </div>
        </div>

        {{-- ==================== MAIN 2-COLUMN SECTION: FORM + MAP ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            {{-- Left: Contact Form (7 cols) --}}
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 lg:p-10 border border-gray-100/90 shadow-sm">
                <div class="mb-6 sm:mb-8">
                    <span class="bg-orange-50 text-[var(--primary)] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-2">
                        {{ __('Bizə Yazın') }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-[color:var(--text-color)]">
                        {{ __('Müraciət Formu') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-[color:var(--grey-text)] mt-1">
                        {{ __('Formu doldurun, mütəxəssislərimiz ən qısa zamanda sizinlə əlaqə saxlasın.') }}
                    </p>
                </div>

                <form action="{{ route('inquiries.contact') }}" method="POST" id="contactForm" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label for="contact_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('Adınız və Soyadınız') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact_name" name="name" required placeholder="{{ __('Məs: Samir Əliyev') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="contact_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('Əlaqə Nömrəniz') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_phone" name="phone" required placeholder="{{ __('+994 50 000 00 00') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label for="contact_email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('E-poçt Ünvanı') }}
                            </label>
                            <input type="email" id="contact_email" name="email" placeholder="{{ __('email@example.com') }}"
                                   class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition">
                        </div>

                        {{-- Service Interest --}}
                        <div>
                            <label for="contact_subject" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                {{ __('Mövzu / Xidmət növü') }}
                            </label>
                            <select id="contact_subject" name="interest"
                                    class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition cursor-pointer">
                                <option value="">{{ __('Seçin...') }}</option>
                                <option value="buy">{{ __('Əmlak Alışı') }}</option>
                                <option value="sell">{{ __('Əmlak Satışı') }}</option>
                                <option value="rent">{{ __('Kirayə Əmlak') }}</option>
                                <option value="agency">{{ __('Agentlik / Rieltor Əməkdaşlığı') }}</option>
                                <option value="other">{{ __('Digər Məsələlər') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="contact_message" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('Mesajınız') }}
                        </label>
                        <textarea id="contact_message" name="message" rows="4" placeholder="{{ __('Sualınızı və ya təklifinizi ətraflı qeyd edin...') }}"
                                  class="w-full px-4 py-3 text-sm bg-gray-50/60 border border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-500 transition resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-between flex-wrap gap-4">
                        <button type="submit" id="contactSubmitBtn"
                                class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                            <span>{{ __('Mesajı Göndər') }}</span>
                            <i class="bi bi-send-fill text-xs"></i>
                        </button>
                        <span class="text-[11px] text-gray-400">
                            <i class="bi bi-shield-check text-green-500 mr-1"></i>{{ __('Məlumatlarınızın təhlükəsizliyi qorunur') }}
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
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base">{{ __('İş Qrafiki') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('Müştəri xidmətləri') }}</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs sm:text-sm text-gray-600">
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                            <span class="font-medium text-gray-800">{{ __('Bazar ertəsi – Cümə:') }}</span>
                            <span class="font-bold text-gray-900">09:00 – 19:00</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                            <span class="font-medium text-gray-800">{{ __('Şənbə:') }}</span>
                            <span class="font-bold text-gray-900">10:00 – 18:00</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 text-gray-500">
                            <span class="font-medium">{{ __('Bazar:') }}</span>
                            <span class="text-orange-500 font-semibold">{{ __('Onlayn müraciət 24/7') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== FAQ BANNER CTA ==================== --}}
        <div class="mt-10 sm:mt-12 bg-orange-500 rounded-3xl p-8 sm:p-10 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="text-xs font-bold uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block mb-2 backdrop-blur-sm">
                    {{ __('Kömək Lazımdır?') }}
                </span>
                <h3 class="text-xl sm:text-2xl font-black">{{ __('Tez-tez verilən suallarınız var?') }}</h3>
                <p class="text-xs sm:text-sm text-orange-100 mt-1.5 leading-relaxed">
                    {{ __('Elan yerləşdirmə, qiymətləndirmə, sənədləşmə və agentliklərlə bağlı ən çox soruşulan sualların cavablarını dərhal FAQ bölməsində tapın.') }}
                </p>
            </div>
            <a href="{{ route('faq') }}"
               class="px-6 sm:px-8 py-3.5 bg-white text-orange-600 hover:bg-orange-50 font-extrabold text-sm rounded-2xl shadow-md transition duration-200 whitespace-nowrap flex items-center gap-2 shrink-0">
                <span>{{ __('FAQ Səhifəsinə Keç') }}</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    window.contactConfig = {
        i18n: {
            sending: "{{ __('Göndərilir...') }}",
            sent: "{{ __('Mesajınız uğurla göndərildi!') }}",
            error: "{{ __('Xəta baş verdi, zəhmət olmasa yenidən cəhd edin.') }}",
            network: "{{ __('Şəbəkə xətası baş verdi.') }}",
            send: "{{ __('Mesajı Göndər') }}"
        }
    };
</script>
<script src="/js/pages/static/contact.js"></script>
@endpush
