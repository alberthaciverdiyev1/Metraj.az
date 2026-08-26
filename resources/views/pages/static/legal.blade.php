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
                                1. {{ __('footer.user_agreement') }} Şartları
                            </h2>
                            <p>
                                KibrisKare.com platformuna hoş geldiniz. Bu Kullanıcı Sözleşmesi ("Sözleşme"), KibrisKare.com ("Platform") web sitesi ve mobil uygulamalarını kullanan tüm ziyaretçiler ve kayıtlı üyeler ("Kullanıcı") için geçerlidir.
                            </p>
                            <p>
                                Platforma erişim sağlayarak veya üye olarak, bu sözleşmede yer alan tüm şartları ve kuralları peşinen kabul etmiş sayılırsınız.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                2. Üyelik ve Hizmet Kullanımı
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li>Kullanıcı, kayıt sırasında doğru, eksiksiz ve güncel bilgiler vermekle yükümlüdür.</li>
                                <li>Hesap güvenliği ve şifre gizliliği tamamen kullanıcının sorumluluğundadır.</li>
                                <li>Platform üzerinden yayınlanan tüm ilanların doğruluğu ve yasallığı ilan sahibine aittir.</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                3. Fikri Mülkiyet ve Haklar
                            </h2>
                            <p>
                                KibrisKare.com'da yer alan logo, tasarım, metin, yazılım ve veri tabanının tüm fikri ve sınai mülkiyet hakları saklıdır. İzinsiz kopyalanamaz veya çoğaltılamaz.
                            </p>
                        </div>
                    @elseif($activeDoc === 'privacy_policy')
                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                1. Kişisel Verilerin Korunması ve Gizlilik
                            </h2>
                            <p>
                                KibrisKare.com olarak kullanıcılarımızın kişisel verilerinin gizliliğine ve güvenliğine en üst düzeyde önem vermekteyiz. Bu Gizlilik Politikası, hangi verilerin toplandığını, nasıl kullanıldığını ve korunduğunu açıklamaktadır.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                2. Toplanan Bilgiler ve Çerezler (Cookies)
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li><strong>Hesap Bilgileri:</strong> İsim, e-posta adresi, telefon numarası.</li>
                                <li><strong>İlan ve Arama Verileri:</strong> Favoriye eklenen ilanlar, arama tercihleri ve konum filtreleri.</li>
                                <li><strong>Teknik Veriler:</strong> IP adresi, tarayıcı türü, ziyaret süresi ve çerezler.</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                3. Verilerin Güvenliği
                            </h2>
                            <p>
                                Kişisel bilgileriniz SSL şifreleme ve güvenli sunucu altyapıları ile korunmakta olup, yasal zorunluluklar dışında üçüncü şahıslarla paylaşılmaz.
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                1. Genel Kullanım Koşulları
                            </h2>
                            <p>
                                Bu kullanım koşulları, KibrisKare.com platformunu ziyaret eden ve ilan yayınlayan tüm gerçek ve tüzel kişiler için bağlayıcıdır.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                2. İlan Verme Kuralları
                            </h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 ml-2">
                                <li>Yayınlanan ilanlar gerçek, güncel ve Kuzey Kıbrıs mevzuatına uygun olmalıdır.</li>
                                <li>Yanıltıcı fiyat, konum veya sahte görseller içeren ilanlar sistem tarafından askıya alınabilir.</li>
                                <li>Her mülk için yalnızca bir aktif ilan oluşturulabilir.</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 border-b border-gray-100 pb-3">
                                3. Sorumluluk Reddi
                            </h2>
                            <p>
                                KibrisKare.com bir ilan ve bilgi paylaşım platformudur. Alıcı ve satıcı arasındaki ticari ve hukuki işlemlerden tarafların kendileri sorumludur.
                            </p>
                        </div>
                    @endif
                </div>
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
