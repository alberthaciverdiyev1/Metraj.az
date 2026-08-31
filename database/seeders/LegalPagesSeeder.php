<?php

namespace Database\Seeders;

use App\Modules\Shared\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $setting = SiteSetting::first() ?? new SiteSetting();

        // 1. KULLANICI SÖZLEŞMESİ (USER AGREEMENT)
        $userAgreement = [
            'tr' => '<h2>1. Taraflar ve Kapsam</h2>
<p>İşbu Kullanıcı Sözleşmesi ("Sözleşme"), <strong>KibrisKare.com</strong> ("Platform / Site") ile Platform\'a üye olan veya ziyaretçi olarak erişim sağlayan gerçek veya tüzel kişiler ("Kullanıcı / Üye") arasında akdedilmiştir. Platform\'u kullanan her kişi, bu sözleşmede belirtilen şartları okumuş, anlamış ve kabul etmiş sayılır.</p>

<h2>2. Hizmetlerin Tanımı</h2>
<p>KibrisKare.com; Kuzey Kıbrıs Türk Cumhuriyeti (KKTC) sınırları dahilinde satılık ve kiralık gayrimenkul (konut, villa, arsa, ticari mülk vb.) ilanlarının, gayrimenkul taleplerinin ("Arıyorum") ve oda arkadaşı ilanlarının yayınlandığı, alıcı, satıcı, kiracı ve yetkili emlak danışmanlarını bir araya getiren bağımsız bir çevrim içi ilan ve bilgi platformudur.</p>

<h2>3. Üyelik ve Hesap Güvenliği</h2>
<ul>
    <li>Kullanıcı, kayıt esnasında verdiği tüm kimlik, telefon ve iletişim bilgilerinin doğru, güncel ve kendisine ait olduğunu kabul ve taahhüt eder.</li>
    <li>Kullanıcı hesaplarının güvenliği (şifre ve giriş bilgilerinin gizliliği) tamamen Kullanıcı\'nın kendi sorumluluğundadır.</li>
    <li>Her Kullanıcı yalnızca bir adet bireysel veya kurumsal hesap açabilir; sahte, yanıltıcı veya başkası adına açılan hesaplar tespit edildiğinde derhal askıya alınır.</li>
</ul>

<h2>4. İlan Yayınlama ve İçerik Sorumluluğu</h2>
<ul>
    <li>Platform\'a eklenen tüm ilanların, görsellerin, fiyatların ve gayrimenkul özelliklerinin hukuki, cezai ve mali sorumluluğu münhasıran ilanı yayınlayan Kullanıcı\'ya / İlan Sahibi\'ne aittir.</li>
    <li>Mülkiyeti veya geçerli pazarlama yetkisi bulunmayan taşınmazlar için ilan verilemez.</li>
    <li>Yanıltıcı, gerçek dışı fiyat veya para birimi içeren, telif hakkı ihlali barındıran ya da mükerrer girilen ilanlar Platform yönetimi tarafından haber verilmeksizin yayından kaldırılabilir.</li>
</ul>

<h2>5. İletişim ve Güvenlik Mekanizmaları</h2>
<p>Platform, kullanıcılarının gizliliğini ve veri güvenliğini korumak amacıyla ilan detaylarında yer alan telefon numaralarını botlara ve kötü niyetli veri çekme (scraping) yazılımlarına karşı maskeli olarak sunar. Telefon gösterim işlemleri güvenlik ve istatistik amacıyla kayıt altına alınmaktadır.</p>

<h2>6. Fikri ve Sınai Mülkiyet Hakları</h2>
<p>KibrisKare.com platformunda yer alan marka, logo, arayüz tasarımı, yazılım kodları, veri tabanı yapısı ve tüm içeriklerin fikri mülkiyet hakları KibrisKare\'ye aittir. Yazılı izin olmaksızın kısmen veya tamamen kopyalanamaz, çoğaltılamaz ve dağıtılamaz.</p>

<h2>7. Sorumluluk Sınırları</h2>
<p>KibrisKare.com, 6563 sayılı Elektronik Ticaretin Düzenlenmesi Hakkında Kanun ve ilgili mevzuat uyarınca "Aracı Hizmet Sağlayıcı" konumundadır. Platform, kullanıcılar veya emlak ofisleri arasında gerçekleşen alım-satım, kiralama, kapora veya sözleşme süreçlerinin tarafı değildir ve bunlardan doğabilecek uyuşmazlıklardan sorumlu tutulamaz.</p>

<h2>8. Uyuşmazlıkların Çözümü ve Yetkili Mahkeme</h2>
<p>İşbu Sözleşme\'nin uygulanmasından doğabilecek her türlü hukuki ihtilafta Kuzey Kıbrıs Türk Cumhuriyeti (KKTC) Mahkemeleri ve İcra Daireleri münhasıran yetkilidir.</p>',

            'az' => '<h2>1. Tərəflər və Əhatə Dairəsi</h2>
<p>Bu İstifadəçi Razılaşması ("Razılaşma"), <strong>KibrisKare.com</strong> ("Platforma / Sayt") ilə Platformaya üzv olan və ya qonaq kimi daxil olan fiziki və ya hüquqi şəxslər ("İstifadəçi / Üzv") arasında bağlanmışdır. Platformadan istifadə edən hər bir şəxs bu qaydaları oxumuş və qəbul etmiş sayılır.</p>

<h2>2. Xidmətlərin Təsviri</h2>
<p>KibrisKare.com; Şimali Kipr Türk Cümhuriyyətində (KKTC) satılıq və kirayə daşınmaz əmlak elanlarının, müştəri tələblərinin ("Axtarıram") və otaq yoldaşı elanlarının yerləşdirildiyi, alıcı, satıcı, kirayəçi və rieltorları birləşdirən onlayn əmlak platformasıdır.</p>

<h2>3. Qeydiyyat və Hesab Təhlükəsizliyi</h2>
<ul>
    <li>İstifadəçi qeydiyyat zamanı təqdim etdiyi bütün məlumatların doğru və aktual olduğunu təsdiq edir.</li>
    <li>Hesabın giriş məlumatlarının və şifrəsinin məxfiliyinə görə birbaşa İstifadəçi cavabdehdir.</li>
    <li>Saxta və ya başqasının adından açılmış hesablar aşkar edildikdə dərhal xəbərdarlıqsız bloklanır.</li>
</ul>

<h2>4. Elanların Yerləşdirilməsi və Məsuliyyət</h2>
<ul>
    <li>Saytda yerləşdirilən bütün elanların məzmununa, şəkillərinə və qiymətinə görə hüquqi məsuliyyət elan sahibinə aiddir.</li>
    <li>Satış və ya kirayə hüququ olmayan əmlakların elanının verilməsi qəti qadağandır.</li>
    <li>Dəqiq olmayan, təkrar və ya aldadıcı məlumatlar Platforma rəhbərliyi tərəfindən silinə bilər.</li>
</ul>

<h2>5. Mübahisələrin Həlli</h2>
<p>Bu Razılaşmadan irəli gələn bütün hüquqi mübahisələrin həllində Şimali Kipr Türk Cümhuriyyəti (KKTC) Məhkəmələri səlahiyyətlidir.</p>',

            'en' => '<h2>1. Parties and Scope</h2>
<p>This User Agreement ("Agreement") is concluded between <strong>KibrisKare.com</strong> ("Platform") and individuals or entities accessing the Platform as registered members or visitors ("User / Member"). By using the Platform, you acknowledge that you have read, understood, and agreed to all the terms stated herein.</p>

<h2>2. Description of Services</h2>
<p>KibrisKare.com is an independent online real estate marketplace connecting property buyers, sellers, tenants, and verified real estate agents across Northern Cyprus (TRNC) for properties for sale, rent, property requests, and shared accommodation.</p>

<h2>3. Membership and Account Security</h2>
<ul>
    <li>Users agree to provide accurate, truthful, and complete personal and contact information upon registration.</li>
    <li>Users are solely responsible for maintaining the confidentiality of their login credentials and passwords.</li>
    <li>Creating duplicate, misleading, or fraudulent accounts is strictly prohibited and results in immediate termination.</li>
</ul>

<h2>4. Property Listings and Content Responsibility</h2>
<ul>
    <li>The legal and commercial responsibility for all property listings, photos, prices, and specifications published on the Platform belongs exclusively to the listing author.</li>
    <li>Listings must represent genuine properties with valid marketing permissions from legal owners.</li>
    <li>Misleading pricing, duplicate entries, or copyrighted materials will be removed without prior notice.</li>
</ul>

<h2>5. Applicable Law and Jurisdiction</h2>
<p>Any legal disputes arising from the use of this Platform shall be subject to the exclusive jurisdiction of the Courts and Execution Offices of the Turkish Republic of Northern Cyprus (TRNC).</p>',

            'ru' => '<h2>1. Стороны и предмет соглашения</h2>
<p>Настоящее Пользовательское соглашение ("Соглашение") регулирует отношения между порталом <strong>KibrisKare.com</strong> ("Платформа") и физическими или юридическими лицами ("Пользователь"), использующими сервисы сайта. Использование Платформы означает полное согласие со всеми условиями настоящего Соглашения.</p>

<h2>2. Описание сервисов</h2>
<p>KibrisKare.com — это независимая онлайн-платформа недвижимости на Северном Кипре (ТРСК), предоставляющая каталог объявлений о продаже, аренде жилой и коммерческой недвижимости, земельных участков, а также раздел заявок на поиск недвижимости.</p>

<h2>3. Регистрация и безопасность учетной записи</h2>
<ul>
    <li>Пользователь обязуется указывать достоверную контактную и персональную информацию при регистрации.</li>
    <li>Пользователь несет полную ответственность за сохранность пароля и доступ к своему личному кабинету.</li>
    <li>Создание фиктивных или вводящих в заблуждение аккаунтов влечет немедленную блокировку.</li>
</ul>

<h2>4. Правила размещения объявлений</h2>
<ul>
    <li>Вся полнота ответственности за содержание, достоверность цен и фотографии в объявлении возлагается на лицо, разместившее объявление.</li>
    <li>Запрещается публикация объектов без законных прав на продажу или аренду.</li>
    <li>Объявления с недостоверными ценами или чужими фотографиями удаляются администрацией.</li>
</ul>

<h2>5. Применимое право и юрисдикция</h2>
<p>Все споры, возникающие в связи с использованием Платформы, подлежат рассмотрению в судах Турецкой Республики Северного Кипра (ТРСК).</p>'
        ];

        // 2. GİZLİLİK POLİTİKASI (PRIVACY POLICY)
        $privacyPolicy = [
            'tr' => '<h2>1. Giriş ve Veri Sorumlusu</h2>
<p><strong>KibrisKare.com</strong> olarak, web sitemizi ve mobil hizmetlerimizi kullanan tüm ziyaretçi ve üyelerimizin kişisel verilerinin korunmasına ve mahremiyetine azami özen göstermekteyiz. Bu Gizlilik Politikası, hangi verileri topladığımızı, bu verilerin nasıl işlendiğini, korunduğunu ve haklarınızı açıklamaktadır.</p>

<h2>2. Toplanan Kişisel Veriler</h2>
<ul>
    <li><strong>Kimlik ve İletişim Bilgileri:</strong> Ad, soyad, e-posta adresi, telefon numarası, WhatsApp iletişim bilgisi.</li>
    <li><strong>İlan ve Talep Bilgileri:</strong> Eklediğiniz gayrimenkul detayları, fotoğraflar, konum ve fiyat tercihleri.</li>
    <li><strong>İşlem ve Güvenlik Bilgileri:</strong> IP adresi, giriş ve arama logları, telefon görüntüleme (reveal) kayıtları, cihaz ve tarayıcı bilgileri.</li>
    <li><strong>Çerezler (Cookies):</strong> Dil ve para birimi (GBP, EUR, USD vb.) tercihlerinizi hatırlamak ve sayfa performansını artırmak amacıyla kullanılan teknik çerezler.</li>
</ul>

<h2>3. Verilerin İşlenme Amaçları</h2>
<ul>
    <li>Platform üzerinden gayrimenkul alım-satım ve kiralama hizmetlerinin sunulması.</li>
    <li>İlan sahipleri ile ilgilenen kullanıcılar arasında güvenli iletişimin sağlanması.</li>
    <li>Kötü niyetli girişimlerin, sahte ilanların ve otomatik veri çekme (scraping) botlarının engellenmesi.</li>
    <li>Kullanıcı deneyiminin iyileştirilmesi, para birimi ve yerel dil tercihlerinin optimize edilmesi.</li>
    <li>Yasal mevzuattan kaynaklanan bilgi saklama ve resmi makamlara bildirim yükümlülüklerinin yerine getirilmesi.</li>
</ul>

<h2>4. Veri Güvenliği ve Koruma Tedbirleri</h2>
<p>Kişisel verileriniz modern SSL/TLS şifreleme protokolleri, güvenlik duvarları ve yetkilendirme katmanları ile korunmaktadır. Telefon numaraları ilk sayfa yüklemesinde açıkta bırakılmaz ve veritabanlarımızda güvenle muhafaza edilir.</p>

<h2>5. Kişisel Verilerin Paylaşımı</h2>
<p>Kişisel verileriniz, kanunen yetkili resmi merciler ve mahkemeler haricinde hiçbir üçüncü taraf reklam veya pazarlama şirketine satılmaz veya izinsiz devredilmez.</p>

<h2>6. Kullanıcı Hakları ve İletişim</h2>
<p>Kullanıcılar diledikleri zaman hesaplarındaki verileri güncelleme, düzeltme veya silinmesini talep etme hakkına sahiptir. Gizlilik ile ilgili talepleriniz için <strong>info@kibriskare.com</strong> üzerinden bizimle iletişime geçebilirsiniz.</p>',

            'az' => '<h2>1. Giriş və Məxfilik Öhdəliyi</h2>
<p><strong>KibrisKare.com</strong> istifadəçilərimizin fərdi məlumatlarının qorunmasına və təhlükəsizliyinə yüksək önəm verir. Bu Məxfilik Siyasəti məlumatlarınızın necə toplanıldığını və qorunduğunu izah edir.</p>

<h2>2. Toplanan Məlumatlar</h2>
<ul>
    <li><strong>Şəxsi və Əlaqə Məlumatları:</strong> Ad, soyad, telefon nömrəsi, e-poçt, WhatsApp nömrəsi.</li>
    <li><strong>Elan Məlumatları:</strong> Əmlak parametrləri, fotoşəkillər və tələb məlumatları.</li>
    <li><strong>Texniki Məlumatlar:</strong> IP ünvanı, giriş loqları, brauzer növü və kuki (cookie) faylları.</li>
</ul>

<h2>3. Məlumatların İstifadə Məqsədləri</h2>
<ul>
    <li>Əmlak alqı-satqı və kirayə xidmətlərinin operativ göstərilməsi.</li>
    <li>Elan sahibi ilə müştəri arasında təhlükəsiz əlaqənin qurulması.</li>
    <li>Platformanın təhlükəsizliyinin təmin olunması və saxtakarlığın qarşısının alınması.</li>
</ul>

<h2>4. Məlumatların Qorunması</h2>
<p>Toplanan bütün məlumatlar SSL şifrələmə və müasir təhlükəsizlik protokolları ilə qorunur. Məlumatlar üçüncü tərəflərə ötürülmür.</p>',

            'en' => '<h2>1. Introduction and Data Protection</h2>
<p>At <strong>KibrisKare.com</strong>, we are committed to protecting the privacy and personal data of our users and visitors. This Privacy Policy outlines how your personal information is collected, processed, and safeguarded.</p>

<h2>2. Information We Collect</h2>
<ul>
    <li><strong>Identity and Contact Data:</strong> Name, phone number, email address, WhatsApp number.</li>
    <li><strong>Listing and Inquiry Data:</strong> Property details, photos, location preferences, and search queries.</li>
    <li><strong>Technical and Usage Data:</strong> IP address, device type, browser information, interaction logs, and essential cookies.</li>
</ul>

<h2>3. Purpose of Processing</h2>
<ul>
    <li>To facilitate real estate transactions and inquiries between property owners, agents, and buyers.</li>
    <li>To secure user accounts and protect phone numbers from automated scraping and spam bots.</li>
    <li>To remember user preferences including default currency (GBP) and language settings.</li>
    <li>To comply with statutory legal and regulatory obligations.</li>
</ul>

<h2>4. Data Security</h2>
<p>We implement robust technical and administrative security measures, including SSL/TLS encryption and strict access controls, to prevent unauthorized access, alteration, or disclosure of your data.</p>',

            'ru' => '<h2>1. Общие положения</h2>
<p>Портал <strong>KibrisKare.com</strong> уделяет приоритетное внимание защите персональных данных своих пользователей. Настоящая Политика конфиденциальности описывает порядок сбора, обработки и защиты вашей информации.</p>

<h2>2. Собираемые данные</h2>
<ul>
    <li><strong>Контактные данные:</strong> Имя, номер телефона, адрес электронной почты, контакты WhatsApp.</li>
    <li><strong>Данные объявлений:</strong> Параметры недвижимости, фотографии, описания и бюджетные предпочтения.</li>
    <li><strong>Технические данные:</strong> IP-адрес, файлы cookie, история посещений и журнал просмотров контактов.</li>
</ul>

<h2>3. Цели обработки данных</h2>
<ul>
    <li>Обеспечение функционирования сервисов каталога недвижимости и связи между покупателями и продавцами.</li>
    <li>Защита телефонных номеров от несанкционированного автоматического сбора (парсинга) и спама.</li>
    <li>Сохранение пользовательских настроек валюты (GBP) и языка.</li>
</ul>

<h2>4. Защита информации</h2>
<p>Мы используем шифрование SSL/TLS и современные средства защиты информации для предотвращения несанкционированного доступа к персональным данным.</p>'
        ];

        // 3. KULLANIM KOŞULLARI (TERMS OF USE)
        $termsOfUse = [
            'tr' => '<h2>1. Genel Kurallar</h2>
<p>KibrisKare.com web sitesini ziyaret eden veya platformda işlem yapan tüm kullanıcılar, yürürlükteki mevzuata, genel ahlak ve dürüstlük kurallarına uymakla yükümlüdür.</p>

<h2>2. İlan Verme ve Yayın Kuralları</h2>
<ul>
    <li><strong>Gerçeklik İlkesi:</strong> İlanlarda yer alan metrekare, oda sayısı, tapu türü (Eşdeğer, Türk Koçanı, Tahsis vb.), kat ve fiyat bilgileri gerçeği tam olarak yansıtmalıdır.</li>
    <li><strong>Para Birimi ve Fiyatlandırma:</strong> İlan fiyatları Kuzey Kıbrıs emlak piyasası standartlarına uygun olarak doğru para birimi (GBP £, EUR €, USD $ veya TL ₺) seçilerek girilmelidir.</li>
    <li><strong>Fotoğraf ve Medya:</strong> İlan fotoğrafları taşınmazın güncel durumunu göstermelidir. Başka sitelerden izinsiz alınan veya üzerinde farklı marka logosu/filigran bulunan görseller kullanılamaz.</li>
    <li><strong>Mükerrer İlan Yasağı:</strong> Aynı taşınmaz için aynı kullanıcı tarafından birden fazla aktif ilan açılamaz.</li>
    <li><strong>Satılan / Kiralanan İlanlar:</strong> Satışı veya kiralaması tamamlanan gayrimenkullerin ilanları derhal ilan sahibi tarafından kapatılmalı veya yayından kaldırılmalıdır.</li>
</ul>

<h2>3. Emlak Talepleri ("Arıyorum") Bölümü Kuralları</h2>
<ul>
    <li>"Arıyorum" bölümü yalnızca gerçek gayrimenkul arayışları (satın alma, kiralama, oda arkadaşı arama) için kullanılabilir.</li>
    <li>Bu bölümde reklam, ticari tanıtım veya emlak dışı duyuruların yayınlanması yasaktır.</li>
</ul>

<h2>4. Kurumsal Üyeler ve Emlak Ofisleri</h2>
<p>Platformda kurumsal üyelik açan emlak ofisleri ve bağımsız danışmanlar, KKTC Emlakçılar Birliği ve ilgili yasal mevzuat kapsamındaki yetki ve lisans kurallarına riayet etmekle yükümlüdür.</p>

<h2>5. Platformun Hak ve Yetkileri</h2>
<p>KibrisKare.com, kurallara aykırı olduğunu tespit ettiği ilanları yayından kaldırma, düzeltme talep etme veya kural ihlali yapan kullanıcı hesaplarını geçici/kalıcı olarak askıya alma hakkını saklı tutar.</p>',

            'az' => '<h2>1. Ümumi Qaydalar</h2>
<p>KibrisKare.com saytına daxil olan və xidmətlərdən istifadə edən hər bir istifadəçi qanunvericiliyə və platformanın ümumi istifadə qaydalarına riayət etməlidir.</p>

<h2>2. Elan Yerləşdirmə Qaydaları</h2>
<ul>
    <li><strong>Məlumatların Düzgünlüyü:</strong> Elanın qiyməti, sahəsi, otaq sayı və sənəd növü (Kupça/Koçan) tam reallığı əks etdirməlidir.</li>
    <li><strong>Şəkillər:</strong> Fotoşəkillər əmlakın real vəziyyətini əks etdirməli, digər saytlardan icazəsiz kopyalanmamalıdır.</li>
    <li><strong>Təkrar Elan Qadağası:</strong> Eyni əmlak üçün təkrar elan yerləşdirmək qadağandır.</li>
    <li><strong>Aktuallıq:</strong> Satılmış və ya kirayə verilmiş əmlak elanları dərhal deaktiv edilməlidir.</li>
</ul>

<h2>3. "Axtarıram" Bölməsinin Qaydaları</h2>
<p>Bu bölmədən yalnız real əmlak axtarışı və tələblər üçün istifadə edilə bilər. Reklam xarakterli məlumatların paylaşılması qadağandır.</p>',

            'en' => '<h2>1. General Conditions</h2>
<p>All users accessing and utilizing the services of KibrisKare.com agree to adhere to all applicable laws, regulations, and these Terms of Use.</p>

<h2>2. Property Listing Rules</h2>
<ul>
    <li><strong>Accuracy:</strong> All property details including pricing, square meters, title deed type (Exchange, Turkish Title, etc.), and room counts must be accurate and truthful.</li>
    <li><strong>Currency & Pricing:</strong> Prices must be specified in appropriate currencies (GBP £, EUR €, USD $, TRY ₺) without misleading discounts.</li>
    <li><strong>Photos & Media:</strong> Images must depict the actual condition of the property and be free of unauthorized third-party watermarks.</li>
    <li><strong>Duplicate Listings:</strong> Publishing multiple listings for the same real estate unit is strictly prohibited.</li>
    <li><strong>Status Updates:</strong> Properties that have been sold or leased must be promptly closed or unpublished by the listing owner.</li>
</ul>

<h2>3. Real Estate Inquiries ("Requests") Guidelines</h2>
<p>The "Requests / Looking For" section must only be used for genuine property search inquiries and shared living arrangements. Commercial spam or irrelevant advertising is prohibited.</p>',

            'ru' => '<h2>1. Общие условия</h2>
<p>Пользователи сайта KibrisKare.com обязуются соблюдать действующее законодательство и настоящие Правила использования.</p>

<h2>2. Правила публикации объявлений</h2>
<ul>
    <li><strong>Достоверность:</strong> Параметры объекта (цена, площадь, тип титула/кочана, этаж) должны строго соответствовать действительности.</li>
    <li><strong>Валюта и цены:</strong> Цены указываются в актуальной валюте (GBP £, EUR €, USD $, TRY ₺) без скрытых комиссий.</li>
    <li><strong>Фотографии:</strong> Фотографии должны отражать реальное состояние объекта и не содержать водяных знаков сторонних порталов.</li>
    <li><strong>Дублирование:</strong> Повторная публикация одного и того же объекта запрещена.</li>
    <li><strong>Актуализация:</strong> Проданные или сданные в аренду объекты должны быть своевременно деактивированы.</li>
</ul>

<h2>3. Раздел заявок на поиск ("Ищу")</h2>
<p>Раздел предназначен исключительно для поиска недвижимости и поиска соседей по комнате. Размещение посторонней рекламы запрещено.</p>'
        ];

        $setting->user_agreement = $userAgreement;
        $setting->privacy_policy = $privacyPolicy;
        $setting->terms_of_use = $termsOfUse;
        $setting->save();

        Cache::forget(SiteSetting::CACHE_KEY);

        echo "Legal pages (User Agreement, Privacy Policy, Terms of Use) seeded successfully into site_settings.\n";
    }
}
