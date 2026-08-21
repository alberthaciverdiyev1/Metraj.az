# Metraj - Layihə İnkişaf Xəritəsi (Development Roadmap)

Bu sənəd layihənin addım-addım necə qurulacağını və hər fazada icra olunacaq işləri göstərir.

---

## 📌 Faza 1: Bünövrə və Təməl Quraşdırma (Foundation & Setup)
- [ ] Laravel layihəsinin yaradılması (`laravel/laravel`)
- [ ] Clean Architecture qovluq iyerarxiyasının təşkili (`app/Core/Domain`, `app/Core/Application`, `app/Core/Infrastructure`)
- [ ] Filament v3 quraşdırılması
- [ ] Multi-Panel konfiqurasiyası:
  - [ ] `AdminPanelProvider` (`/admin`)
  - [ ] `AgencyPanelProvider` (`/agency`)
- [ ] Tailwind CSS, Alpine.js və Blade strukturunun inteqrasiyası

---

## 📌 Faza 2: Domain Modeli və Verilənlər Bazası (Domain & Schema)
- [ ] **Location Modulu:**
  - Şəhərlər (Cities), Rayonlar (Districts), Qəsəbələr/Məhəllələr (Neighborhoods), Metro stansiyaları (MetroStations)
- [ ] **Agency & User Modulu:**
  - İstifadəçi rolları: Super Admin, Moderator, Agency Owner, Agent, Regular User
  - Agentlik profili: Loqo, lisenziya, ünvan, əlaqə nömrələri, sosial media
- [ ] **Property Modulu:**
  - Tiplər: Mənzil, Həyət evi/Villa, Obyekt, Ofis, Torpaq, Qaraj
  - Əməliyyat növü: Satış, Aylıq Kirayə, Günlük Kirayə
  - Parametrlər: Sahə, otaq sayı, mərtəbə, bina mərtəbəsi, təmir vəziyyəti, çıxarış (kupça), ipoteka yararlılığı
  - Xüsusiyyətlər (Amenities): Qaz, su, işıq, lift, parkinq, mebel, kondisioner, balkon və s.
  - Şəkillər və Planlar (Media library)
- [ ] **Inquiry & Lead Modulu:**
  - Müştəri müraciətləri, baxış istəkləri (Viewing requests)

---

## 📌 Faza 3: Clean Architecture Use Case və Repozitoriyalar
- [ ] Repository İnterfeyslərinin yaradılması (`Domain/`)
- [ ] Eloquent Repozitoriyalarının implementasiyası (`Infrastructure/Persistence/`)
- [ ] DTO və Use Case-lərin yazılması (`Application/`):
  - `CreatePropertyUseCase`, `UpdatePropertyUseCase`, `PublishPropertyUseCase`
  - `RegisterAgencyUseCase`, `InviteAgentUseCase`
  - `SubmitInquiryUseCase`
- [ ] Service Provider-lərdə Interface-Binding əlaqələrinin qurulması

---

## 📌 Faza 4: Filament Admin və Agency Panellərinin Təşkili
- [ ] **Admin Panel:**
  - Property Resource (Təsdiqləmə, redaktə, VIP statusu təyini)
  - Agency Resource (Agentlikləri təsdiqləmək və idarə etmək)
  - Location Resources (Şəhər, rayon, metro)
  - Amenity & Category Resources
- [ ] **Agency Panel:**
  - Agentlik daxili elanların idarəsi (Agency Property Resource)
  - Agentlərin idarə olunması (Agent Resource)
  - Müştəri müraciətləri və zəng/WhatsApp statistikası
  - Agentlik profilinin redaktəsi

---

## 📌 Faza 5: Frontend (Blade) İnterfeysi
- [ ] Əsas Səhifə (Hero search, Kateqoriyalar, VIP/Premium elanlar, Son əlavə olunanlar, Partnyor Agentliklər)
- [ ] Əmlak Kataloqu və Filtrasiya Səhifəsi (Sürətli AJAX/Alpine və ya URL əsaslı axtarış)
- [ ] Əmlak Detal Səhifəsi (Foto qalereya, Xəritə, Əmlak xüsusiyyətləri, Əlaqəli elanlar, Agent əlaqə kartı)
- [ ] Agentliklər və Agentlər Səhifəsi
- [ ] Əlaqə və Elan Yerləşdirmə Müraciəti

---

## 📌 Faza 6: Testlər, Optimallaşdırma və Təhlükəsizlik
- [ ] Unit & Feature testlər (Pest / PHPUnit)
- [ ] Verilənlər bazası indekslənməsi və axtarış optimallaşdırması
- [ ] Şəkillərin avtomatik optimallaşdırılması (WebP, Responsive sizes)
- [ ] SEO Meta Teqlər, OpenGraph və Sitemap
