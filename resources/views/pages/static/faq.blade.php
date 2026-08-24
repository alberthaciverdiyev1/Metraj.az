@extends('layouts.app')

@section('title', __('Tez-tez Verilən Suallar (FAQ) - Metraj.az'))

@section('content')
<div class="w-full pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    {{-- ==================== HERO / HEADER SECTION ==================== --}}
    <section class="mt-4 sm:mt-6 bg-white rounded-3xl p-6 sm:p-10 border border-gray-100/90 shadow-sm text-center">
        <div class="max-w-2xl mx-auto space-y-3">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                {{ __('Tez-tez Verilən Suallar') }}
            </h1>

            {{-- Live Search Box --}}
            <div class="pt-3 max-w-lg mx-auto">
                <div class="relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="faqSearchInput" placeholder="{{ __('Sual və ya açar söz axtarın...') }}"
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
                        {{ __('Hamısı') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="general">
                        {{ __('Ümumi & Qeydiyyat') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="listings">
                        {{ __('Elan Yerləşdirmə') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="payments">
                        {{ __('VIP & Ödənişlər') }}
                    </button>
                    <button type="button" class="faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200" data-category="safety">
                        {{ __('Təhlükəsizlik') }}
                    </button>
                </div>

                {{-- Accordion List Container --}}
                <div id="faqAccordionContainer" class="space-y-4">

                    {{-- Category 1: Ümumi --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Metraj.az nədir və necə işləyir?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Metraj.az daşınmaz əmlak alqı-satqısı və kirayəsi üzrə müasir və etibarlı elan platformasıdır. Burada həm fərdi mülkiyyətçilər, həm də peşəkar agentliklər və rieltorlar öz elanlarını rahatlıqla yerləşdirə və minlərlə real alıcı ilə əlaqə qura bilərlər.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Elanlara baxmaq üçün qeydiyyatdan keçmək məcburidirmi?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Xeyr, saytdakı bütün aktiv elanlarla tanış olmaq, filtrləmək və satıcılarla əlaqə saxlamaq tamamilə açıqdır. Lakin öz elanlarınızı idarə etmək və sevimlilər siyahısı yaratmaq üçün profil açmağınız tövsiyə olunur.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="general">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Şəxsi hesabımın məlumatlarını necə dəyişə bilərəm?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Giriş etdikdən sonra sağ yuxarı küncdə yerləşən istifadəçi menyusundan "Profil" bölməsinə daxil olaraq ad, soyad, əlaqə telefonu, email və şifrənizi istənilən vaxt yeniləyə bilərsiniz.') }}
                        </div>
                    </div>

                    {{-- Category 2: Elan Yerləşdirmə --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Saytda necə yeni elan yerləşdirə bilərəm?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Yuxarı sağ paneldəki "Yeni Elan" düyməsinə klikləyərək formanı açın. Əmlak növünü, qiymətini, yerləşdiyi ünvanı xəritədə qeyd edin, fotoşəkilləri yükləyin və təsdiqə göndərin. Qonaq istifadəçilər də qeydiyyat olmadan birbaşa elan yerləşdirə bilərlər.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Əlavə etdiyim elan nə vaxt saytda görünəcək?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Elanın dəqiqliyini və keyfiyyətini təmin etmək üçün bütün yeni elanlar moderator yoxlanışından keçir. Moderator yoxlanışı adətən 15-30 dəqiqə ərzində tamamlanır və elanınız dərhal aktiv edilir.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="listings">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Şəkillərlə bağlı hansı tələblər mövcuddur?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Elana ən azı 1, maksimum isə 20 ədəd real və aydın fotoşəkil əlavə etmək mümkündür. Formatlar: JPG, PNG, WebP. İlk seçilən şəkil elanın əsas örtük şəkli olur.') }}
                        </div>
                    </div>

                    {{-- Category 3: VIP & Ödənişlər --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Standart elan yerləşdirmək ödənişlidirmi?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Xeyr, Metraj.az platformasında standart daşınmaz əmlak elanı yerləşdirmək tamamilə pulsuzdur. Əlavə xidmətlər (VIP, Premium, İrəli çəkmə) isə könüllü olaraq seçilə bilər.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('VIP və Seçilmiş elan xidmətinin nə kimi üstünlükləri var?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('VIP və Seçilmiş elanlar həm ana səhifədə, həm də axtarış və kateqoriya nəticələrində hər səhifənin ilk 10 sırasında xüsusi nişanla nümayiş olunur. Bu isə elanınızın 5-10 dəfə daha sürətli satılmasına və ya kirayə verilməsinə kömək edir.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="payments">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Ödənişləri hansı üsullarla həyata keçirə bilərəm?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Bütün yerli və xarici bank kartları (Visa, Mastercard), həmçinin onlayn bankçılıq tətbiqləri ilə təhlükəsiz şəkildə 3D Secure sistemi ilə ödəniş edə bilərsiniz.') }}
                        </div>
                    </div>

                    {{-- Category 4: Təhlükəsizlik --}}
                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="safety">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Şəxsi məlumatlarımın təhlükəsizliyinə necə zəmanət verilir?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Metraj.az məlumatların məxfiliyinə tam zəmanət verir. Daxil etdiyiniz email və əlaqə vasitələri heç bir üçüncü tərəfə ötürülmür və yalnız elanınızın statusu barədə bildiriş göndərmək üçün istifadə olunur.') }}
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="safety">
                        <button type="button" class="faq-trigger w-full px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between text-left gap-4 hover:bg-gray-50/50 transition">
                            <span class="font-semibold text-sm sm:text-base text-gray-900">{{ __('Şübhəli elan və ya dələduzluqla qarşılaşdıqda nə etməliyəm?') }}</span>
                            <span class="faq-icon w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform duration-200">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </span>
                        </button>
                        <div class="faq-content hidden px-5 sm:px-6 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ __('Hər bir elanın daxilində "Şikayət et" funksiyası mövcuddur. Həmçinin dərhal qaynar xəttimiz və ya dəstək formu vasitəsilə bizə müraciət edə bilərsiniz. Moderator heyətimiz elanı dərhal araşdıracaqdır.') }}
                        </div>
                    </div>

                    {{-- No Search Results placeholder --}}
                    <div id="noFaqResults" class="hidden bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto text-2xl mb-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4 class="font-semibold text-base text-gray-900">{{ __('Axtarışa uyğun sual tapılmadı') }}</h4>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                            {{ __('Fərqli açar sözlərlə axtarış edin və ya birbaşa dəstək komandamızla əlaqə saxlayın.') }}
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
                            <h3 class="font-bold text-gray-900 text-base leading-tight">{{ __('Kömək Lazımdır?') }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ __('Bizimlə əlaqə saxlayın') }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">
                        {{ __('Axtardığınız sualın cavabını tapmadınızsa, peşəkar dəstək komandamız sizə kömək etməyə hazırdır.') }}
                    </p>

                    <div class="space-y-2.5 pt-2 border-t border-gray-100 text-xs">
                        <a href="tel:+994501234567" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-orange-50/60 transition group">
                            <i class="bi bi-telephone-fill text-orange-500 text-sm"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-[11px] text-gray-400">{{ __('Qaynar Xətt') }}</span>
                                <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition">+994 50 123 45 67</span>
                            </div>
                        </a>

                        <a href="https://wa.me/994501234567" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-emerald-50/60 transition group">
                            <i class="bi bi-whatsapp text-emerald-600 text-sm"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-[11px] text-gray-400">WhatsApp</span>
                                <span class="font-semibold text-gray-900 group-hover:text-emerald-600 transition">+994 50 123 45 67</span>
                            </div>
                        </a>

                        <a href="mailto:info@metraj.az" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-orange-50/60 transition group">
                            <i class="bi bi-envelope-fill text-orange-500 text-sm"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-[11px] text-gray-400">Email</span>
                                <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition">info@metraj.az</span>
                            </div>
                        </a>
                    </div>

                    <a href="{{ route('contact') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow transition duration-200 text-xs">
                        <span>{{ __('Əlaqə Səhifəsinə Keç') }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Fast Add Listing Promo --}}
                <div class="bg-gray-900 rounded-3xl p-6 sm:p-7 text-white shadow-sm space-y-4">
                    <span class="inline-block bg-orange-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded-md">
                        {{ __('Pulsuz') }}
                    </span>
                    <h3 class="text-base sm:text-lg font-semibold leading-snug">
                        {{ __('Öz əmlakınızı dərhal satışa və ya kirayəyə çıxarın') }}
                    </h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        {{ __('Cəmi 2 dəqiqə ərzində elanınızı yerləşdirin və minlərlə potensial müştəriyə çatın.') }}
                    </p>
                    <a href="{{ route('add-property') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-900 hover:bg-orange-500 hover:text-white font-semibold text-xs rounded-xl shadow transition duration-200">
                        <span>{{ __('Elan Yerləşdir') }}</span>
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>

            </div>

        </div>
    </section>
</div>

@push('scripts')
    <script src="{{ asset('js/pages/static/faq.js') }}"></script>
@endpush
@endsection
