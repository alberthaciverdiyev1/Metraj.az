<?php

namespace Database\Seeders;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            // ============ MƏSLƏHƏT ============
            [
                'category' => 'Məsləhət',
                'title' => 'Bakıda mənzil alarkən nəyə diqqət etməli?',
                'excerpt' => 'İlk evinizi alarkən ən vacib 7 məqam: sənədlər, rayon seçimi, qiymət analizi və s.',
                'content' => 'Bakıda mənzil almaq böyük qərar və investisiyadır. İlk növbədə çıxarış (kupça) sənədini yoxlayın, sonra rayonun infrastrukturunu və nəqliyyat əlçatanlığını araşdırın. Qiymətləri eyni rayondakı oxşar mənzillərlə müqayisə edin və mütləq müstəqil ekspert qiymətləndirməsi aparın.',
            ],
            [
                'category' => 'Məsləhət',
                'title' => 'Kirayə mənzil seçərkən 5 vacib meyar',
                'excerpt' => 'Kirayə mənzil axtararkən nəzərə alınmalı meyarlar: büdcə, ünvan, vəziyyət və müqavilə şərtləri.',
                'content' => 'Kirayə mənzil seçərkən büdcənizi dəqiq müəyyənləşdirin, evin metrolara və iş yerinizə yaxınlığını yoxlayın. Müqaviləni mütləq yazılı bağlayın və depozit şərtlərini əvvəlcədən razılaşdırın.',
            ],
            [
                'category' => 'Məsləhət',
                'title' => 'Torpaq sahəsi almaq istəyirsiniz? Bunları bilin',
                'excerpt' => 'Torpaq alqı-satqısında diqqət edilməli qanuni tələblər və sənədlər haqqında.',
                'content' => 'Torpaq sahəsi alarkən torpağın məqsəd təyinatını (kənd təsərrüfatı, yaşayış, kommersiya) mütləq yoxlayın. Kadastr sənədləri və mülkiyyət hüququ qeydiyyatı olmadan alqı-satqı etməyin.',
            ],
            [
                'category' => 'Məsləhət',
                'title' => 'Mənzil satarkən qiyməti necə düzgün təyin etməli?',
                'excerpt' => 'Həddindən artıq baha və ya ucuz qiymət qoymadan evinizi tez satmağın yolları.',
                'content' => 'Evinizi satarkən eyni rayondakı ən azı 10 oxşar elanı təhlil edin. Kvadrat metra görə ortalama qiyməti hesablayın və təmir vəziyyətinə görə əmsal tətbiq edin. Real bazar qiyməti həm sürətli satış, həm də yaxşı qazanc deməkdir.',
            ],
            [
                'category' => 'Məsləhət',
                'title' => 'İpoteka ilə ev almağın 7 addımı',
                'excerpt' => 'İpoteka krediti üçün tələb olunan sənədlər və prosesin mərhələləri.',
                'content' => 'İpoteka ilə ev almaq üçün əvvəlcə bankın tələblərini (aylıq gəlir, ilkin ödəniş, kredit tarixçəsi) öyrənin. İlkin ödəniş adətən dəyərin 15-20%-i qədərdir. Proses: banka müraciət, sənəd təqdimatı, qiymətləndirmə, müqavilə və qeydiyyat.',
            ],
                        
            // ============ BAZAR ============
            [
                'category' => 'Bazar',
                'title' => '2026-cı ildə Bakı daşınmaz əmlak bazarı necədir?',
                'excerpt' => 'Bakıda mənzil qiymətlərinin son tendensiyaları və 2026 proqnozu.',
                'content' => '2026-cı ildə Bakıda mənzil qiymətlərində yüngül artım müşahidə olunur. Ən çox tələb 1-3 otaqlı mənzillərə və mərkəzə yaxın rayonlara olur. Yeni yaşayış komplekslərinin tikintisi təklifi artırsa da, keyfiyyətli obyektlərə tələb sabit qalır.',
            ],
            [
                'category' => 'Bazar',
                'title' => 'Bakının ən bahalı rayonları hansılardır?',
                'excerpt' => 'Qiymət reytinqində öndə gedən rayonlar və kvadratmetr qiymətləri.',
                'content' => 'Bakıda ən yüksək qiymətlər Nəsimi, Yasamal və Xətai rayonlarının mərkəzi hissələrində qeydə alınır. Burada 1 m² orta hesabla 1500-2500 AZN arasında dəyişir. Nizami küçəsi və Fəvvarələr meydanı ətrafı isə rekord göstəricilərə malikdir.',
            ],
            [
                'category' => 'Bazar',
                'title' => 'Kirayə qiymətləri niyə artır?',
                'excerpt' => 'Bakıda kirayə bazarında qiymət artımının səbəbləri və gələcək proqnoz.',
                'content' => 'Şəhərə tələbatın artması, yeni iş yerləri və tələbələrin axını kirayə qiymətlərini yuxarı çəkir. Mərkəzi rayonlarda 1 otaqlı mənzilin aylıq kirayəsi 400-600 AZN arasında dəyişir.',
            ],
            [
                'category' => 'Bazar',
                'title' => 'Kommersiya obyektlərinə investisiya: gəlirli mi?',
                'excerpt' => 'Ofis, mağaza və anbar obyektlərinə investisiyanın gəlirliliyi.',
                'content' => 'Kommersiya obyektləri yaşayış evlərinə nisbətən daha yüksək gəlir gətirə bilər. Mağaza və ofislərin illik gəlirliyi orta hesabla 8-12% təşkil edir, lakin boş qalma riski də nəzərə alınmalıdır.',
            ],
                        
            // ============ XƏBƏR ============
            [
                'category' => 'Xəbər',
                'title' => 'Yeni yaşayış kompleksi: Yasamalda 500 mənzil',
                'excerpt' => 'Yasamal rayonunda inşasına başlanan yeni kompleks haqqında məlumat.',
                'content' => 'Yasamal rayonunda 12 mərtəbəli, 500 mənzildən ibarət yeni yaşayış kompleksinin tikintisinə başlanılıb. Kompleksdə yeraltı parkinq, uşaq bağçası və idman zalı nəzərdə tutulub. Tikintinin 2027-ci ildə yekunlaşması gözlənilir.',
            ],
            [
                'category' => 'Xəbər',
                'title' => 'Metro stansiyalarına yaxın mənzillər bahalaşır',
                'excerpt' => 'Metro infrastrukturu daşınmaz əmlak qiymətlərinə necə təsir edir?',
                'content' => 'Analizlərə görə, yeni metro stansiyalarının açılması ətraf ərazilərdə mənzil qiymətlərini orta hesabla 8-15% artırır. Metroya 5-10 dəqiqəlik məsafədəki mənzillər həm alıcılar, həm də kirayəçilər üçün daha cəlbedicidir.',
            ],
            [
                'category' => 'Xəbər',
                'title' => 'Elektron qeydiyyat sistemi alqı-satqını sadələşdirir',
                'excerpt' => 'Daşınmaz əmlak əməliyyatlarının elektron qaydada qeydiyyatı.',
                'content' => 'Yeni elektron qeydiyyat sistemi mənzil alqı-satqısı prosesini xeyli sadələşdirib. Artıq sənədlərin əksəriyyəti onlayn təqdim olunur və əməliyyat 1-2 iş günü ərzində tamamlanır.',
            ],
            [
                'category' => 'Xəbər',
                'title' => 'Evin qiymətləndirilməsi artıq məcburidir',
                'excerpt' => 'Bank krediti üçün mənzil qiymətləndirməsinin yeni qaydaları.',
                'content' => 'Yeni tənzimləməyə əsasən, bank krediti ilə mənzil alarkən müstəqil qiymətləndirmə məcburidir. Qiymətləndirmə aktı əməliyyatın daha şəffaf və təhlükəsiz olmasına xidmət edir.',
            ],
                        [
                'category' => 'Xəbər',
                'title' => 'Bakıda 20 yeni park tikiləcək',
                'excerpt' => 'Şəhər infrastrukturu layihəsi ətraf ərazilərin cəlbediciliyini artırır.',
                'content' => 'Bakıda 2027-ci ilə qədər 20 yeni park və istirahət zonasının tikintisi planlaşdırılır. Bu layihələr ətraf rayonlarda yaşayış keyfiyyətini artıraraq daşınmaz əmlakın dəyərinə müsbət təsir göstərir.',
            ],

            // ============ İNVESTİSİYA ============
            [
                'category' => 'İnvestisiya',
                'title' => 'Kiçik büdcə ilə əmlak investisiyası necə başlamalı?',
                'excerpt' => 'Az kapitala başlayanlar üçün praktik investisiya strategiyaları.',
                'content' => 'Kiçik büdcə ilə əmlak investisiyasına başlamaq üçün ən yaxşı yol qaraj və ya kiçik kommersiya obyekti almaqdır. Daha sonra gəliri yığıb daha böyük obyektə keçə bilərsiniz. Əsas qayda: heç vaxt borclanaraq həddindən artıq riskə girməyin.',
            ],
            [
                'category' => 'İnvestisiya',
                'title' => 'Kirayə gəliri ilə gəlirli investisiya portfeli',
                'excerpt' => 'Çoxmənzilli kirayə biznesi necə qurulur?',
                'content' => 'Kirayə biznesi qurmaq üçün əvvəlcə yüksək tələbat olan ərazilərdə mənzil alın. Hər bir mənzil aylıq gəlir gətirir və uzun müddətdə mənzilin dəyəri də artır. 3-5 mənzildən ibarət portfel sabit passiv gəlir mənbəyidir.',
            ],
            [
                'category' => 'İnvestisiya',
                'title' => 'Köhnə ev alıb təmir edərək satmaq strategiyası',
                'excerpt' => 'Flip investisiya: ucuz al, təmir et, baha sat.',
                'content' => 'Flip strategiyası köhnə və baxımsız evləri ucuz alıb, təmir edərək bazar qiymətindən satmaqdır. Təmirə xərclənən hər manat mənzilin dəyərini orta hesabla 2-3 manat artırır.',
            ],
            [
                'category' => 'İnvestisiya',
                'title' => 'Yeni tikililərdə ilkin satış dövründə almaq',
                'excerpt' => 'Tikinti mərhələsində mənzil alaraq 20-30% qazanc əldə etmək.',
                'content' => 'Yeni yaşayış komplekslərinin ilkin satış mərhələsində qiymətlər sonrakı dövrlərə nisbətən 20-30% ucuz olur. Tikinti tamamlandıqda mənzilin dəyəri artır və siz ya satıb qazanc əldə edə, ya da kirayəyə verə bilərsiniz.',
            ],
                        
            // ============ HÜQUQİ ============
            [
                'category' => 'Hüquqi',
                'title' => 'Mənzil alqı-satqısı müqaviləsində nələr olmalıdır?',
                'excerpt' => 'Alqı-satqı müqaviləsinin əsas şərtləri və vacib bəndlər.',
                'content' => 'Alqı-satqı müqaviləsində tərəflərin məlumatları, obyektin tam təsviri, qiymət, ödəniş qaydası və çatdırılma müddəti mütləq göstərilməlidir. Müqavilə notarial qaydada təsdiqlənməlidir.',
            ],
            [
                'category' => 'Hüquqi',
                'title' => 'Çıxarış (kupça) nədir və necə yoxlanılır?',
                'excerpt' => 'Mülkiyyət sənədinin yoxlanılması qaydası.',
                'content' => 'Çıxarış mənzilin qanuni sahibini təsdiq edən rəsmi sənəddir. Alış-verişdən əvvəl əmlakın üzərində həbs, girov və ya başqa məhdudiyyətin olmadığını yoxlamaq üçün Daşınmaz Əmlak Dövlət Qeydiyyatı Xidmətindən çıxarış tələb edin.',
            ],
            [
                'category' => 'Hüquqi',
                'title' => 'Torpaq sahələrinin qanuni qeydiyyatı',
                'excerpt' => 'Torpaq mülkiyyət hüququnun qeydiyyatı və sənədləşmə prosesi.',
                'content' => 'Torpaq sahəsinin qanuni qeydiyyatı üçün kadastr planı, mülkiyyət sənədi və ödənilmiş dövlət rüsumu tələb olunur. Qeydiyyat prosesi adətən 10-15 iş günü çəkir.',
            ],
            [
                'category' => 'Hüquqi',
                'title' => 'Kirayə müqaviləsində mülkiyyətçinin hüquqları',
                'excerpt' => 'Ev sahiblərinin hüquqları və kirayəçi ilə münasibətlər.',
                'content' => 'Mülkiyyətçinin əsas hüquqları: vaxtında ödəniş almaq, əmlakın vəziyyətini yoxlamaq və müqavilə şərtləri pozulduqda müqaviləni ləğv etmək. Bütün hüquqlar yazılı müqavilə ilə təmin olunmalıdır.',
            ],
                        
            // ============ HƏYAT TƏRZİ ============
            [
                'category' => 'Həyat tərzi',
                'title' => 'Mənzildə işıqlı məkanın sirri: düzgün işıqlandırma',
                'excerpt' => 'Balaca mənzilləri daha geniş və işıqlı göstərməyin yolları.',
                'content' => 'Balaca mənzillərdə ağ və pastel rənglər, böyük güzgülər və çoxsəviyyəli işıqlandırma məkanı vizual olaraq genişləndirir. Təbii işığı maksimum dərəcədə buraxan pərdələr seçin.',
            ],
            [
                'category' => 'Həyat tərzi',
                'title' => 'Yeni evə köçəndə nələrə diqqət etməli?',
                'excerpt' => 'Köçmə prosesini asanlaşdıran yoxlama siyahısı.',
                'content' => 'Yeni evə köçmədən əvvəl su və qaz sayğaclarını yoxlayın, elektrik panelini yoxlayın, qapı və pəncərələrin möhkəmliyini təmin edin. Köçdükdən sonra ilk həftə ərzində bütün sənədləri (müqavilə, çıxarış) təhlükəsiz yerdə saxlayın.',
            ],
            [
                'category' => 'Həyat tərzi',
                'title' => 'Kiçik mənzili rahat yaşayış üçün necə təşkil etməli?',
                'excerpt' => 'Kompakt yaşayış üçün ağıllı mebel və təşkilatçılıq həlləri.',
                'content' => 'Kiçik mənzillərdə transformasiya olunan mebellər (çarpayı-sofa, qatlanan masa) və divar rəfləri məkandan maksimum istifadə etməyə kömək edir. Hündür şkaflar şaquli məkanı effektiv istifadə edir.',
            ],
                                    [
                'category' => 'Həyat tərzi',
                'title' => 'Balkonu necə rahat istirahət guşəsinə çevirmək olar?',
                'excerpt' => 'Balaca balkonda rahat oturacaq və dekorasiya ideyaları.',
                'content' => 'Balkonunuzu istirahət guşəsinə çevirmək üçün kompakt oturacaqlar, xalça və isti işıqlandırma kifayətdir. Bitki rəfləri və divar dekorları məkanı fərdiləşdirir.',
            ],

            // ============ TEXNİKİ ============
            [
                'category' => 'Texniki',
                'title' => 'Mənzilin istilik sistemi necə seçilməlidir?',
                'excerpt' => 'Mərkəzi istilik, qaz və elektrik sistemlərinin müqayisəsi.',
                'content' => 'Mənzil seçərkən istilik sisteminə diqqət yetirin. Mərkəzi istilik sistemli evlər daha qənaətlidir, qaz kombisi isə fərdi nəzarət imkanı verir. Elektrik isitmə kiçik mənzillər üçün əlverişlidir.',
            ],
            [
                'category' => 'Texniki',
                'title' => 'Ev alarkən elektrik naqillərini yoxlayın',
                'excerpt' => 'Köhnə naqillərin riskləri və yoxlama üsulları.',
                'content' => 'Köhnə tikililərdə elektrik naqilləri yükə tab gətirməyə bilər. Alışdan əvvəl mütləq elektrik sistemini peşəkar yoxlatdırın. Zəif naqil həm yanğın riski yaradır, həm də müasir məişət texnikasının işləməsinə mane olur.',
            ],
            [
                'category' => 'Texniki',
                'title' => 'Su təchizatı və kanalizasiya sisteminin yoxlanması',
                'excerpt' => 'Mənzildə su problemlərini əvvəlcədən aşkarlamağın yolları.',
                'content' => 'Su təchizatı sistemini yoxlamaq üçün bütün kranları açın, tualetin sıxlığını və su sayğacının düzgün işlədiyini yoxlayın. Köhnə mənzillərdə boruların dəyişdirilmə ehtiyacını da nəzərə alın.',
            ],
            [
                'category' => 'Texniki',
                'title' => 'Dam örtüyü və bina izolyasiyası niyə vacibdir?',
                'excerpt' => 'Binanın xarici vəziyyətinin daxili yaşayışa təsiri.',
                'content' => 'Binanın dam örtüyü və izolyasiyası evin istiliyinə və rütubətinə birbaşa təsir edir. Zəif izolyasiya qışda isitmə xərclərini artırır və divarlarda nəm əmələ gətirir.',
            ],
                                ];

        $imageIndex = 0;
        $images = [
            'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80', // building
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80', // house
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', // living room
            'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&q=80', // apartment
            'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&q=80', // interior
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80', // apartment interior
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80', // room
            'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=800&q=80', // modern home
            'https://images.unsplash.com/photo-1449844908441-8829872d2607?w=800&q=80', // house exterior
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', // office building
        ];

        foreach ($blogs as $i => $blog) {
            $title = $blog['title'];
            $slug = \Illuminate\Support\Str::slug($title);

            // Eyni başlığa düşən təkrarlanma hallarının qarşısını almaq üçün
            if (Blog::where('slug', $slug)->exists()) {
                $slug .= '-' . ($i + 1);
            }

            Blog::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'category' => $blog['category'],
                    'cover_image' => $images[$i % count($images)],
                    'excerpt' => $blog['excerpt'],
                    'content' => $blog['content'],
                    'published_at' => now()->subDays($i),
                ]
            );
        }
    }
}
