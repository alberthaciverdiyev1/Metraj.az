# KibrisKare - Müasir Emlak Platforması (Real Estate Platform)

KibrisKare — Clean Architecture prinsipləri əsasında qurulmuş, çoxpanelli (Admin & Agency) və yüksək performanslı bir emlak platformasıdır.

---

## 🚀 Texnoloji Struktur

- **Framework:** Laravel (v11/v12)
- **Admin & Agency İdarəetmə Paneli:** Filament PHP v3
  - `/admin` — Sistem Administratoru Paneli
  - `/agency` — Daşınmaz Əmlak Agentlikləri Paneli (Multi-tenant)
- **Ön Hissə (Frontend):** Laravel Blade + Tailwind CSS + Alpine.js
- **Arxitektura:** Clean Architecture (Domain, Application, Infrastructure, Presentation)

---

## 🏛 Layihə Arxitekturası (Clean Architecture)

Layihə təmiz memarlıq standartlarına uyğun olaraq aşağıdakı laylardan ibarətdir:

1. **Domain Layer (`app/Core/Domain`):**
   - Biznes obyektləri, Value Object-lər, Enum-lar, Domain Event-lər və Repository İnterfeysləri.
2. **Application Layer (`app/Core/Application`):**
   - Use Case-lər / Əməliyyat məntiqləri, DTO-lar (Data Transfer Objects).
3. **Infrastructure Layer (`app/Core/Infrastructure`):**
   - Eloquent modelləri, Miqrasiyalar, Repository Implementasiyaları, Xarici Xidmətlər (Media, SMS, Xəritə).
4. **Presentation Layer (`app/Filament`, `app/Http`):**
   - Filament Admin Paneli, Filament Agency Paneli və Blade Web Controller / Görünüşləri.

Ətraflı arxitektura sənədi: [ARCHITECTURE.md](file:///home/albert/Workspace/FoxSoft/KibrisKare/ARCHITECTURE.md)

---

## 🔑 Əsas Xüsusiyyətlər

### 1. Filament Admin Paneli (`/admin`)
- Bütün elanların moderasiyası və təsdiqlənməsi
- Agentliklərin və agentlərin qeydiyyatı və təsdiqi
- Kateqoriyalar, Şəhər/Rayon/Qəsəbə/Metro stansiyaları idarəsi
- Xüsusiyyətlər (Təmir, sənəd növü, qaz, kupça və s.)
- Reklam və VIP/Premium elan idarəetməsi
- Sistem istifadəçiləri və hüquqlar (Roles & Permissions)

### 2. Filament Agency Paneli (`/agency`)
- Agentlik daxilində agentlərin (maklerlərin) idarə olunması
- Agentliyin öz elanlarının yerləşdirilməsi və redaktəsi
- Müştəri müraciətlərinin (Leads / Inquiries) qəbulu və agentlərə yönləndirilməsi
- Agentliyin elan statistikası və baxış sayı

### 3. Ziyarətçi Saytı (Blade Frontend)
- Sürətli və çevik filtrasiya (Alqı-Satqı / İcarə, Əmlak növü, Qiymət, Otaq sayı, Sahə, Rayon, Metro)
- Xəritə ilə axtarış və yaxınlıqdakı obyektlər
- Əmlakın detallı səhifəsi (Şəkillər qalereyası, 360 virtual tur, agent ilə birbaşa əlaqə/WhatsApp)
- Agentliklərin ictimai kataloqu və agent profilləri
- Mobil uyğun (Responsive) dizayn

---

## 🛠 Quraşdırma (Installation)

### Tələblər
- PHP >= 8.2 (bcmath, ctype, fileinfo, mbstring, openssl, pdo, tokenizer, xml)
- Composer 2.x
- Node.js >= 18.x & NPM
- MySQL >= 8.0 və ya PostgreSQL >= 14

### Addımlar

```bash
# 1. Asılılıqları yükləyin
composer install
npm install

# 2. Mühit konfiqurasiyası
cp .env.example .env
php artisan key:generate

# 3. Verilənlər bazası miqrasiyası və ilkin məlumatlar
php artisan migrate --seed

# 4. Storage link
php artisan storage:link

# 5. Filament İstifadəçilərini yaratmaq
php artisan make:filament-user

# 6. Frontend resurslarını hazırlamaq və serveri başlatmaq
npm run build
php artisan serve
```

---

## 📁 Sənədlər
- [ARCHITECTURE.md](file:///home/albert/Workspace/FoxSoft/KibrisKare/ARCHITECTURE.md) - Clean Architecture strukturu və qaydalar
- [ROADMAP.md](file:///home/albert/Workspace/FoxSoft/KibrisKare/ROADMAP.md) - Layihənin inkişaf mərhələləri və tapşırıqlar
