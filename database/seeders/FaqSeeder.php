<?php

namespace Database\Seeders;

use App\Modules\Shared\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Category: general
            [
                'category' => 'general',
                'sort_order' => 1,
                'is_active' => true,
                'question' => [
                    'tr' => 'KıbrısKare nedir ve nasıl çalışır?',
                    'az' => 'KıbrısKare nədir və necə işləyir?',
                    'en' => 'What is KibrisKare and how does it work?',
                    'ru' => 'Что такое KibrisKare и как это работает?',
                ],
                'answer' => [
                    'tr' => 'KıbrısKare, Kuzey Kıbrıs’ta (KKTC) gayrimenkul alım-satımı, kiralama, oda arkadaşı arama ve gayrimenkul talep ilanları için geliştirilmiş modern emlak platformudur. Bireysel mülk sahipleri, acenteler ve danışmanlar ilanlarını güvenle yayınlayabilir.',
                    'az' => 'KıbrısKare Şimali Kiprdə (KKTC) daşınmaz əmlak alqı-satqısı, kirayəsi, otaq yoldaşı və əmlak tələbləri üzrə müasir elan platformasıdır. Həm fərdi mülkiyyətçilər, həm də agentliklər elanlarını yerləşdirə bilərlər.',
                    'en' => 'KibrisKare is a modern real estate platform in Northern Cyprus (TRNC) for buying, selling, renting properties, finding roommates, and posting property requests. Property owners, agencies, and agents can safely list properties.',
                    'ru' => 'KibrisKare — это современная платформа недвижимости на Северном Кипре (ТРСК) для покупки, продажи, аренды недвижимости, поиска соседей по комнате и запросов. Владельцы, агентства и агенты могут безопасно размещать объявления.',
                ],
            ],
            [
                'category' => 'general',
                'sort_order' => 2,
                'is_active' => true,
                'question' => [
                    'tr' => 'İlanlara bakmak için üye olmak zorunlu mu?',
                    'az' => 'Elanlara baxmaq üçün qeydiyyatdan keçmək məcburidirmi?',
                    'en' => 'Is registration required to browse property listings?',
                    'ru' => 'Обязательна ли регистрация для просмотра объявлений?',
                ],
                'answer' => [
                    'tr' => 'Hayır, sitedeki tüm satılık ve kiralık ilanları incelemek, filtrelemek ve satıcılarla doğrudan iletişime geçmek tamamen ücretsiz ve üyeliksizdir. Ancak ilanlarınızı takip etmek ve favorilere eklemek için ücretsiz hesap açmanız önerilir.',
                    'az' => 'Xeyr, saytdakı bütün elanlarla tanış olmaq, filtrləmək və satıcılarla birbaşa əlaqə saxlamaq tamamilə açıqdır və qeydiyyat tələb olunmur.',
                    'en' => 'No, browsing, filtering, and contacting sellers/agents on KibrisKare is completely open and free without registration. Creating a free account is recommended to save favorites and manage listings.',
                    'ru' => 'Нет, просмотр всех объявлений, фильтрация и связь с продавцами полностью бесплатны и не требуют регистрации.',
                ],
            ],
            [
                'category' => 'general',
                'sort_order' => 3,
                'is_active' => true,
                'question' => [
                    'tr' => 'KıbrısKare hangi şehir ve bölgeleri kapsıyor?',
                    'az' => 'KıbrısKare hansı şəhər və bölgələri əhatə edir?',
                    'en' => 'Which cities and regions does KibrisKare cover?',
                    'ru' => 'Какие города и регионы охватывает KibrisKare?',
                ],
                'answer' => [
                    'tr' => 'Platformumuz Lefkoşa, Girne, Gazimağusa, İskele, Güzelyurt ve Lefke başta olmak üzere Kuzey Kıbrıs’ın tüm şehir ve mahallelerindeki ilanları kapsamaktadır.',
                    'az' => 'Platformamız Lefkoşa, Girne, Qazimağusa, İskele, Gözəlyurd və Lefke daxil olmaqla Şimali Kiprin bütün şəhər və qəsəbələrini əhatə edir.',
                    'en' => 'Our platform covers all cities and districts across Northern Cyprus, including Nicosia (Lefkosa), Kyrenia (Girne), Famagusta (Gazimagusa), Iskele, Guzelyurt, and Lefke.',
                    'ru' => 'Наша платформа охватывает все города и районы Северного Кипра, включая Никосию (Лефкоша), Кирению (Гирне), Фамагусту (Газимагуса), Искеле, Гюзельюрт и Лефке.',
                ],
            ],

            // Category: listings
            [
                'category' => 'listings',
                'sort_order' => 4,
                'is_active' => true,
                'question' => [
                    'tr' => 'Sitede nasıl yeni ilan verebilirim?',
                    'az' => 'Saytda necə yeni elan yerləşdirə bilərəm?',
                    'en' => 'How can I post a new property listing?',
                    'ru' => 'Как подать новое объявление о недвижимости?',
                ],
                'answer' => [
                    'tr' => 'Sağ üst menüdeki "İlan Ver" butonuna tıklayarak formu açın. İlan başlığı, gayrimenkul türü (Daire, Villa, Arsa vb.), fiyat, para birimi (GBP, EUR, USD, TL), oda sayısı, harita konumu ve fotoğrafları ekleyerek ilanınızı onaya gönderebilirsiniz.',
                    'az' => 'Yuxarı sağ menyudakı "Elan Yerləşdir" düyməsinə klikləyərək formanı açın. Əmlak növünü, qiymətini, valyutasını, xəritədə yerini və şəkilləri qeyd edib təsdiqə göndərə bilərsiniz.',
                    'en' => 'Click the "Post Listing" button in the top menu. Fill in property type, price, currency (GBP, EUR, USD, TRY), room count, map location, upload photos, and submit for approval.',
                    'ru' => 'Нажмите кнопку "Подать объявление" в верхнем меню. Заполните тип недвижимости, цену, валюту (GBP, EUR, USD, TL), количество комнат, координаты на карте, загрузите фото и отправьте на модерацию.',
                ],
            ],
            [
                'category' => 'listings',
                'sort_order' => 5,
                'is_active' => true,
                'question' => [
                    'tr' => 'Eklediğim ilan ne zaman sitede görünür?',
                    'az' => 'Əlavə etdiyim elan nə vaxt saytda aktiv olacaq?',
                    'en' => 'When will my listing appear on the website?',
                    'ru' => 'Когда мое объявление появится на сайте?',
                ],
                'answer' => [
                    'tr' => 'İlanların gerçekliğini ve kalitesini güvence altına almak için ilanlar moderatör kontrolünden geçirilir. Kontrol süreci genellikle 15-30 dakika içinde tamamlanır ve ilan anında yayına alınır.',
                    'az' => 'Bütün yeni elanlar keyfiyyət və dəqiqlik baxımından moderator nəzarətindən keçir. Yoxlanış adətən 15-30 dəqiqə ərzində tamamlanır və elan yayına buraxılır.',
                    'en' => 'To ensure accuracy and trust, listings undergo moderator review. This usually takes 15-30 minutes, after which your listing goes live immediately.',
                    'ru' => 'Все новые объявления проходят быструю проверку модератором (обычно 15–30 минут) для обеспечения качества и достоверности.',
                ],
            ],
            [
                'category' => 'listings',
                'sort_order' => 6,
                'is_active' => true,
                'question' => [
                    'tr' => 'İlan fotoğrafları için kurallar ve limitler nelerdir?',
                    'az' => 'Şəkillərlə bağlı hansı qaydalar və limitlər var?',
                    'en' => 'What are the photo guidelines and limits?',
                    'ru' => 'Каковы правила и лимиты для фотографий?',
                ],
                'answer' => [
                    'tr' => 'İlanınıza en az 1, en fazla 20 adet yüksek kaliteli fotoğraf ekleyebilirsiniz. Desteklenen formatlar JPG, PNG ve WebP’dir. İlk yüklediğiniz görsel kapak fotoğrafı olarak kullanılır.',
                    'az' => 'Elana ən az 1, ən çox 20 ədəd yüksək keyfiyyətli şəkil əlavə edə bilərsiniz (JPG, PNG, WebP). İlk şəkil əsas üzlük fotosu təyin olunur.',
                    'en' => 'You can upload between 1 and 20 high-quality photos (JPG, PNG, WebP). The first uploaded image serves as the main listing cover.',
                    'ru' => 'Вы можете загрузить от 1 до 20 качественных фото (JPG, PNG, WebP). Первое фото используется в качестве обложки объявления.',
                ],
            ],
            [
                'category' => 'listings',
                'sort_order' => 7,
                'is_active' => true,
                'question' => [
                    'tr' => 'Oda arkadaşı veya Gayrimenkul Arama Talebi nasıl verilir?',
                    'az' => 'Otaq yoldaşı və ya Əmlak Axtarış Tələbi elanı necə verilir?',
                    'en' => 'How can I post a Roommate or Property Request listing?',
                    'ru' => 'Как разместить объявление о поиске соседа или запрос на недвижимость?',
                ],
                'answer' => [
                    'tr' => 'Üst menüden "Oda Arkadaşı" veya "Arıyorum" (Talep) sayfalarına girerek doğrudan ilan formunu doldurabilir, bütçenizi, tercih ettiğiniz bölgeyi ve kriterlerinizi belirterek ilan açabilirsiniz.',
                    'az' => 'Menyudan "Otaq Yoldaşı" və ya "Axtarıram" bölmələrinə daxil olaraq büdcənizi və istəklərinizi qeyd edib elanınızı pulsuz yerləşdirə bilərsiniz.',
                    'en' => 'Navigate to the "Roommates" or "Requests" page from the main menu and fill out the dedicated form with your budget, preferred area, and preferences.',
                    'ru' => 'Перейдите в раздел "Соседи" или "Ищу недвижимость" в главном меню и заполните форму, указав бюджет, район и предпочтения.',
                ],
            ],

            // Category: payments
            [
                'category' => 'payments',
                'sort_order' => 8,
                'is_active' => true,
                'question' => [
                    'tr' => 'KıbrısKare\'de standart ilan vermek ücretli mi?',
                    'az' => 'Standart elan yerləşdirmək ödənişlidirmi?',
                    'en' => 'Is posting a standard listing free on KibrisKare?',
                    'ru' => 'Является ли размещение стандартного объявления бесплатным?',
                ],
                'answer' => [
                    'tr' => 'Hayır, KıbrısKare platformunda standart gayrimenkul, oda arkadaşı ve arayış ilanı vermek tamamen ücretsizdir.',
                    'az' => 'Xeyr, standart daşınmaz əmlak, otaq yoldaşı və axtarış elanları tamamilə ödənişsizdir.',
                    'en' => 'No, standard property listings, roommate ads, and property request postings are completely free of charge.',
                    'ru' => 'Нет, стандартные объявления о недвижимости, поиске соседей и запросы на покупку/аренду полностью бесплатны.',
                ],
            ],
            [
                'category' => 'payments',
                'sort_order' => 9,
                'is_active' => true,
                'question' => [
                    'tr' => 'VIP ve Öne Çıkarılan İlan hizmeti nedir?',
                    'az' => 'VIP və Önə Çıxarılan Elan xidməti nədir?',
                    'en' => 'What are VIP and Featured Listing services?',
                    'ru' => 'Что такое услуги VIP и Продвижение объявлений?',
                ],
                'answer' => [
                    'tr' => 'İlanınızı VIP veya Öne Çıkarılan statüsüne yükselterek ana sayfada, arama sonuçlarının en üst sıralarında özel rozet ve renkle sergileyebilir, ilanınıza gelen görüntülenme ve geri dönüş oranını 5-10 kat artırabilirsiniz.',
                    'az' => 'VIP və İrəli Çəkilmiş elanlar ana səhifədə və axtarış nəticələrinin ən üst pilləsində xüsusi nişanla göstərilir, bu da əmlakınızın qat-qat sürətlə satılmasına/kirayə verilməsinə kömək edir.',
                    'en' => 'VIP and Featured listings appear with distinctive badges at the top of search results and homepage sections, dramatically increasing visibility and inquiries.',
                    'ru' => 'VIP и продвигаемые объявления отображаются на первых позициях с яркими значками, многократно увеличивая просмотры и звонки покупателей.',
                ],
            ],
            [
                'category' => 'payments',
                'sort_order' => 10,
                'is_active' => true,
                'question' => [
                    'tr' => 'Fiyatlar hangi para birimlerinde görüntülenebilir?',
                    'az' => 'Qiymətlər hansı valyutalarda göstərilir?',
                    'en' => 'Which currencies are supported on the platform?',
                    'ru' => 'Какие валюты поддерживаются на платформе?',
                ],
                'answer' => [
                    'tr' => 'KıbrısKare platformunda ilanlar İngiliz Sterlini (GBP - £), Euro (EUR - €), Amerikan Doları (USD - $) ve Türk Lirası (TL - ₺) para birimlerinde görüntülenebilir ve filtrelenebilir. Üst bardaki kur seçiciden anlık çevrim yapabilirsiniz.',
                    'az' => 'Platformamızda qiymətlər İngilis Funtu (GBP - £), Avro (EUR - €), ABŞ Dolları (USD - $) və Türk Lirəsi (TL - ₺) ilə göstərilir və anlıq məzənnə ilə avtomatik çevrilir.',
                    'en' => 'Prices can be viewed and filtered in British Pounds (GBP £), Euros (EUR €), US Dollars (USD $), and Turkish Lira (TRY ₺) with live conversion rates.',
                    'ru' => 'Цены отображаются и конвертируются в фунтах стерлингов (GBP £), евро (EUR €), долларах США (USD $) и турецких лирах (TRY ₺).',
                ],
            ],

            // Category: safety
            [
                'category' => 'safety',
                'sort_order' => 11,
                'is_active' => true,
                'question' => [
                    'tr' => 'Kişisel bilgilerim ve telefon numaram güvende mi?',
                    'az' => 'Şəxsi məlumatlarım və əlaqə nömrəm güvəndədirmi?',
                    'en' => 'Is my personal and contact information secure?',
                    'ru' => 'Защищены ли мои личные данные и номер телефона?',
                ],
                'answer' => [
                    'tr' => 'Evet. KıbrısKare kişisel verilerinizi KVKK ve uluslararası gizlilik standartlarına uygun olarak korur. Bilgileriniz asla izniniz olmadan üçüncü taraflarla paylaşılmaz.',
                    'az' => 'Bəli. Məlumatlarınız tam məxfilik qaydaları çərçivəsində qorunur və heç vaxt üçüncü tərəflərə ötürülmür.',
                    'en' => 'Yes. KibrisKare safeguards all personal data under strict privacy standards. Your contact details are never shared with unauthorized third parties.',
                    'ru' => 'Да. Все персональные данные защищены в соответствии с международными стандартами конфиденциальности.',
                ],
            ],
            [
                'category' => 'safety',
                'sort_order' => 12,
                'is_active' => true,
                'question' => [
                    'tr' => 'Şüpheli veya hatalı bir ilanla karşılaşırsam ne yapmalıyım?',
                    'az' => 'Şübhəli və ya yanlış məlumatlı elan görsəm nə etməliyəm?',
                    'en' => 'What should I do if I encounter a suspicious or inaccurate listing?',
                    'ru' => 'Что делать при обнаружении подозрительного объявления?',
                ],
                'answer' => [
                    'tr' => 'İlan detay sayfasındaki "Şikayet Et" butonunu kullanarak veya destek hattımız / iletişim sayfamız üzerinden bize bildirebilirsiniz. Moderatör ekibimiz şikayeti ivedilikle inceleyerek gerekli işlemi yapacaktır.',
                    'az' => 'Elan səhifəsindəki "Şikayət et" düyməsi və ya Əlaqə bölməmiz vasitəsilə dərhal bizə məlumat verə bilərsiniz. Moderator heyətimiz dərhal lazımi tədbirləri görür.',
                    'en' => 'Use the "Report Listing" action on the listing details page or reach out via our contact form. Our moderation team reviews every report promptly.',
                    'ru' => 'Используйте кнопку "Пожаловаться" на странице объявления или напишите нам через форму обратной связи для оперативной проверки.',
                ],
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::updateOrCreate(
                [
                    'category' => $faqData['category'],
                    'sort_order' => $faqData['sort_order'],
                ],
                $faqData
            );
        }
    }
}
