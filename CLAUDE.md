# Metraj - Real Estate Platform Architecture

Bu sənəd layihənin arxitekturasını, qovluq strukturunu, dizayn prinsiplərini və konvensiyalarını təsvir edir.

---

## 1. Texnoloji Stek

- **Backend:** Laravel 11.x / 12.x (PHP 8.2+)
- **Admin & Agency Panel:** Filament PHP 3.x (Multi-Panel: `admin` və `agency`)
- **Frontend:** Laravel Blade + Alpine.js + Tailwind CSS
- **Verilənlər Bazası:** MySQL / PostgreSQL
- **Cache & Queue:** Redis
- **Media İdarəetmə:** Spatie MediaLibrary / Local & S3 Storage

---

## 2. Clean Architecture Layları

Clean Architecture prinsiplərinə uyğun olaraq layihə 4 əsas təbəqəyə bölünür:

```
src/ (və ya app/Core/)
├── Domain/           (Domain Layer - Biznes qaydaları və interfeyslər)
├── Application/      (Application Layer - Use Case-lər və DTO-lar)
├── Infrastructure/   (Infrastructure Layer - Eloquent, DB, Third-party inteqrasiyalar)
└── Presentation/     (Presentation Layer - Filament Admin, Filament Agency, Blade Web)
```

### 2.1. Domain Layer (`src/Domain`)
- **Məqsəd:** Təmiz biznes məntiqi. Çərçivələrdən (Laravel, Filament və s.) asılı deyil.
- **Tərkibi:**
  - **Entities / Models:** Əsas biznes varlıqları (Property, Agency, Agent, Inquiry, Location, Feature).
  - **Value Objects:** Dəyişməz obyektlər (Məs: `Price`, `Coordinates`, `Area`, `PhoneNumber`).
  - **Enums:** Biznes statusları (Məs: `PropertyStatus`, `PropertyType`, `DealType`, `AgencyStatus`).
  - **Repository Interfaces:** Məlumatların saxlanılması və oxunması üçün interfeyslər (Məs: `PropertyRepositoryInterface`).
  - **Domain Events:** Biznes hadisələri (Məs: `PropertyPublishedEvent`, `AgencyVerifiedEvent`).
  - **Domain Exceptions:** Biznes xətaları (Məs: `PropertyAlreadySoldException`).

### 2.2. Application Layer (`src/Application`)
- **Məqsəd:** Tətbiqin ssenariləri (Use Cases) və biznes əməliyyatlarının idarə olunması.
- **Tərkibi:**
  - **Use Cases / Actions:** Hər bir əməliyyat üçün tək məsuliyyətli (Single Responsibility) siniflər:
    - `CreatePropertyUseCase`
    - `UpdatePropertyStatusUseCase`
    - `RegisterAgencyUseCase`
    - `SearchPropertiesUseCase`
  - **Data Transfer Objects (DTO):** Təbəqələr arasında verilənlərin daşınması (Məs: `CreatePropertyDTO`, `PropertyFilterDTO`).
  - **Queries / Handlers:** Məlumat oxumaq üçün xüsusi sorğu obyektləri.

### 2.3. Infrastructure Layer (`src/Infrastructure`)
- **Məqsəd:** Xarici alətlərlə, verilənlər bazası və xidmətlərlə əlaqə.
- **Tərkibi:**
  - **Persistence / Eloquent:**
    - Eloquent Modelləri və Miqrasiyalar
    - Repository Implementasiyaları (`EloquentPropertyRepository implements PropertyRepositoryInterface`)
  - **External Services:** SMS göndərişi, Xəritə/Geocoding (Google Maps, OpenStreetMap), Ödəniş sistemləri.
  - **Storage:** Fayl yükləmə və media idarəetmə servisləri.

### 2.4. Presentation Layer (`src/Presentation` & `app/Filament` & `routes/`)
- **Məqsəd:** İstifadəçi interfeysi və istifadəçidən gələn sorğuların qəbulu.
- **Tərkibi:**
  - **1. Filament Admin Panel (`/admin`):**
    - Super Admin və Menecerlər üçün.
    - Əmlakların təsdiqi/moderasiyası, agentliklərin idarəsi, kateqoriyalar, qiymət paketləri, sistem ayarları.
  - **2. Filament Agency Panel (`/agency`):**
    - Agentlik sahibləri və agentlər üçün xüsusi panel (Multi-tenancy / Agency Scope).
    - Agentliyin elanları, agentlərin idarəsi, gələn müraciətlər/müştərilər (Leads), statistika.
  - **3. Web (Blade):**
    - Saytın ön hissəsi (Ziyarətçilər və axtarış edənlər üçün).
    - Əmlak axtarışı, filtrləmə, ətraflı baxış, agentlik kataloqu, əlaqə formaları.
    - View Models & Form Requests (Validasiya).

---

## 3. Directory Layout (Qovluq Strukturu)

```
app/
├── Core/ (və ya src/)
│   ├── Domain/
│   │   ├── Property/
│   │   │   ├── Enums/
│   │   │   │   ├── PropertyType.php (Apartment, House, Villa, Commercial, Land)
│   │   │   │   ├── DealType.php (Sale, RentDaily, RentMonthly)
│   │   │   │   └── PropertyStatus.php (Draft, PendingApproval, Published, Rejected, Sold, Rented)
│   │   │   ├── ValueObjects/
│   │   │   │   ├── Price.php
│   │   │   │   └── LocationCoordinate.php
│   │   │   ├── Repositories/
│   │   │   │   └── PropertyRepositoryInterface.php
│   │   │   └── Events/
│   │   │       └── PropertyCreatedEvent.php
│   │   ├── Agency/
│   │   │   ├── Enums/AgencyStatus.php
│   │   │   └── Repositories/AgencyRepositoryInterface.php
│   │   ├── Location/
│   │   │   └── Repositories/LocationRepositoryInterface.php
│   │   └── Inquiry/
│   │       └── Repositories/InquiryRepositoryInterface.php
│   │
│   ├── Application/
│   │   ├── Property/
│   │   │   ├── DTOs/
│   │   │   │   ├── CreatePropertyDTO.php
│   │   │   │   └── PropertyFilterDTO.php
│   │   │   └── UseCases/
│   │   │       ├── CreatePropertyUseCase.php
│   │   │       ├── UpdatePropertyUseCase.php
│   │   │       └── SearchPropertiesUseCase.php
│   │   ├── Agency/
│   │   │   ├── DTOs/RegisterAgencyDTO.php
│   │   │   └── UseCases/RegisterAgencyUseCase.php
│   │   └── Inquiry/
│   │       ├── DTOs/CreateInquiryDTO.php
│   │       └── UseCases/SubmitInquiryUseCase.php
│   │
│   └── Infrastructure/
│       ├── Persistence/
│       │   ├── Eloquent/
│       │   │   ├── Models/
│       │   │   │   ├── Property.php
│       │   │   │   ├── Agency.php
│       │   │   │   ├── Agent.php
│       │   │   │   ├── Category.php
│       │   │   │   ├── City.php
│       │   │   │   ├── District.php
│       │   │   │   ├── Amenity.php
│       │   │   │   └── Inquiry.php
│       │   │   └── Repositories/
│       │   │       ├── EloquentPropertyRepository.php
│       │   │       ├── EloquentAgencyRepository.php
│       │   │       └── EloquentLocationRepository.php
│       └── Services/
│           └── Media/
│
├── Filament/
│   ├── Admin/
│   │   ├── Resources/
│   │   │   ├── PropertyResource.php
│   │   │   ├── AgencyResource.php
│   │   │   ├── UserResource.php
│   │   │   └── LocationResource.php
│   │   └── Pages/
│   └── Agency/
│       ├── Resources/
│       │   ├── AgencyPropertyResource.php
│       │   ├── AgentResource.php
│       │   └── InquiryResource.php
│       └── Pages/
│
├── Http/
│   ├── Controllers/Web/
│   │   ├── HomeController.php
│   │   ├── PropertyController.php
│   │   ├── AgencyController.php
│   │   └── InquiryController.php
│   ├── Requests/
│   └── ViewModels/
│
└── Providers/
    ├── DomainServiceProvider.php
    ├── RepositoryServiceProvider.php
    ├── Filament/
    │   ├── AdminPanelProvider.php
    │   └── AgencyPanelProvider.php
    └── AppServiceProvider.php
```

---

## 4. Panellər və Rollar

### 4.1. Admin Panel (`/admin`)
- Giriş: Super Admin, Moderatorlar.
- İcazələr: Bütün sistemə nəzarət, agentlikləri təsdiqləmək, elanları yoxlamaq, kateqoriya və rayonları tənzimləmək.

### 4.2. Agency Panel (`/agency`)
- Giriş: Agentlik rəhbərləri və agentlər.
- Multi-tenancy: Agentlik rəhbəri yalnız öz şirkətinə aid agentləri, elanları və müştəri müraciətlərini görür.

### 4.3. Public Web (Blade)
- Giriş: İctimaiyyət / Ziyarətçilər.
- Funksionallıq: Müasir UX/UI ilə axtarış, xəritə üzərindən axtarış, filtrasiya, agentliklə əlaqə saxlamaq, zəng və WhatsApp düymələri.

---

## 5. Clean Architecture Tələbləri & Qaydalar

1. **Controller və Filament Resource-lar incə olmalıdır (Thin Controllers / Thin Resources):**
   - Heç bir birbaşa ağır biznes məntiqi Controller və ya Filament Resource içində yazılmır.
   - Əməliyyatlar müvafiq `UseCase` / `Action` çağırılaraq həyata keçirilir.
2. **Dependency Inversion:**
   - Use Case-lər və Servislər birbaşa `Eloquent` modellərindən deyil, `Domain Repository Interface`-lərindən asılı olur.
3. **Məlumat axını:**
   - `Request` -> `DTO` -> `UseCase` -> `Repository Interface` -> `Eloquent Repository` -> `Database`.
4. **Tip Təhlükəsizliyi (Type Safety):**
   - Bütün metodlarda PHP 8.2+ tiplər (Strict Types, Enums, Readonly DTOs) istifadə olunmalıdır.
