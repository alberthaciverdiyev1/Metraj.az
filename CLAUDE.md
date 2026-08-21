# Metraj - Real Estate Platform Architecture

Bu sənəd layihənin arxitekturasını, qovluq strukturunu, dizayn prinsiplərini və konvensiyalarını təsvir edir.

---

## 1. Texnoloji Stek

- **Backend:** Laravel 13.x (PHP 8.2+)
- **Admin & Agency Panel:** Filament PHP 3.x (Multi-Panel: `admin` və `agency`)
- **Frontend:** Laravel Blade + Alpine.js + Tailwind CSS
- **Verilənlər Bazası:** MySQL / PostgreSQL
- **Cache & Queue:** Redis
- **Media İdarəetmə:** Spatie MediaLibrary / Local & S3 Storage

---

## 2. Modullu (Modular Monolith) Arxitektura

> Bu layihə **modular monolith** strukturundadır: hər biznes modul öz bütün təbəqələrini özündə saxlayır
> (routes, models, requests, resources, views, controllers, services, repositories, DTOs, enums).

Hər modul `app/Modules/{Modul}/` qovluğunda tam müstəqildir. Modullar arası asılılıqlar yalnız
modul qovluğundakı Eloquent modelləri və provider-lər üzərindən keçir.

Məlumat axını hər modul daxilində:

```
Controllers → Services → Repositories → Eloquent Models → Database
```

### Modullar

| Modul | Qovluq | Məzmun |
|-------|--------|--------|
| **Property** | `app/Modules/Property/` | Elanlar, əmlak card-ları, filtrasiya, axtarış |
| **Agency** | `app/Modules/Agency/` | Agentliklər, agentlər, onların görünüşləri |
| **Blog** | `app/Modules/Blog/` | Bloq yazıları |
| **Inquiry** | `app/Modules/Inquiry/` | Müştəri sorğuları |
| **Location** | `app/Modules/Location/` | Şəhərlər, rayonlar, amenitilər, filtrlər |
| **Shared** | `app/Modules/Shared/` | Auth, dashboard, statik səhifələr, valyuta/dil |

### Modul daxili təbəqələr

**1. Controllers** — `app/Modules/{Modul}/Controllers/`
- Sorğunu qəbul edir, validasiya edir, Service çağırır, view qaytarır.
- Controller daxilində **birbaşa Eloquent sorğusu yazılmır** (`::where`, `::find`, `::create`, `::with` və s.).

**2. Services** — `app/Modules/{Modul}/Services/`
- Biznes məntiqi burada cəmlənir.
- `PropertyService`, `AgencyService`, `AgentService`, `BlogService`, `LocationService`, `InquiryService`, `CurrencyService` (Shared).
- Köməkçi servislər: `PropertyTitleBuilder`, `PropertyPricePresenter`.
- Service-lər repository-ləri çağırır; sadə oxuma sorğularında model-ləri birbaşa da işlədə bilər.

**3. Repositories & Contracts** — ayrı qovluqlar
- **Concrete implementasiyalar:** `app/Modules/{Modul}/Repositories/` — `PropertyRepository`, `AgencyRepository`, `AgentRepository`, `BlogRepository`, `InquiryRepository`.
- **Interfaces (kontraktlar):** `app/Modules/{Modul}/Contracts/` — `PropertyRepositoryInterface`, `AgencyRepositoryInterface`, `AgentRepositoryInterface`, `BlogRepositoryInterface`, `InquiryRepositoryInterface`.
- Concrete sınıflar interface-ləri `implements` edir; interface yalnız kontrakt sənədidir.
- Service-lər constructor-də **concrete** repository-ni qəbul edir (interface yox) — Laravel DI avtomatik çözür, bind-provider yoxdur.
- Bütün verilənlər bazası sorğuları repository-lərdə cəmlənir.
- Controller-lər repository-ləri **birbaşa çağırmır** — həmişə Service vasitəsilə.

**4. Models (Eloquent)** — `app/Modules/{Modul}/Models/`
- Laravel Eloquent modelləri; DB cədvəlləri ilə işləyir.
- Modullar arası modellərə istinad edən `belongsTo`/`hasMany`-lər mütləq `use` ilə import edilir (məs: Property → `use App\Modules\Agency\Models\Agency;`).

**5. Enums, DTOs, Requests, Resources**
- **Enums:** `app/Modules/{Modul}/Enums/` — `PropertyStatus`, `PropertyType`, `DealType`, `SellerType`, `FilterKey`, `AgencyStatus`...
- **DTOs:** `app/Modules/{Modul}/DTOs/` — `CreatePropertyDTO`, `PropertyFilterDTO` (təbəqələr arası məlumat daşımaq üçün).
- **Requests:** `app/Modules/{Modul}/Requests/` — Form Request sinifləri (`StorePropertyRequest`, `StoreInquiryRequest`).
- **Resources:** `app/Modules/{Modul}/Resources/` — API Resource sinifləri (`CityResource`, `DistrictResource`).

**6. Views** — `resources/views/` (merkezi)
- Bütün Blade view-lar modulların içində deyil, **merkezi `resources/views/` altında** saxlanılır.
- View-lar səhifəyə görə qovluqlarda: `pages/property/`, `pages/blog/`, `pages/agency/`, `agencies/`, `agents/`, `pages/static/`, `pages/auth/`, `components/`.
- View istinadları namespace prefixi **olmadan** yazılır: `view('pages.property.list')`, `@include('components.property-card')`, `<x-connect-agent/>`.

**7. Routes** — `app/Modules/{Modul}/Routes/web.php`
- Hər modulun öz route faylı var; `ModuleServiceProvider` `web` middleware qrupu ilə yükləyir.

---

## 3. Qovluq Strukturu

```
app/
├── Modules/
│   ├── Property/
│   │   ├── Controllers/ (HomeController, PropertyDetailController, AddPropertyController)
│   │   ├── Services/ (PropertyService, PropertyTitleBuilder, PropertyPricePresenter)
│   │   ├── Contracts/ (PropertyRepositoryInterface)
│   │   ├── Repositories/ (PropertyRepository)
│   │   ├── Models/ (Property, PropertyImage)
│   │   ├── DTOs/ (CreatePropertyDTO, PropertyFilterDTO)
│   │   ├── Requests/ (StorePropertyRequest)
│   │   ├── Enums/ (PropertyStatus, PropertyType, DealType, SellerType, BuildingType, RepairType)
│   │   └── Routes/web.php
│   ├── Agency/
│   │   ├── Controllers/ (AgencyDetailController, AgencyListController, AgentDetailController)
│   │   ├── Services/ (AgencyService, AgentService)
│   │   ├── Contracts/ (AgencyRepositoryInterface, AgentRepositoryInterface)
│   │   ├── Repositories/ (AgencyRepository, AgentRepository)
│   │   ├── Models/ (Agency, Agent)
│   │   ├── Enums/ (AgencyStatus)
│   │   └── Routes/web.php
│   ├── Blog/
│   │   ├── Controllers/ (BlogController)
│   │   ├── Services/ (BlogService)
│   │   ├── Contracts/ (BlogRepositoryInterface)
│   │   ├── Repositories/ (BlogRepository)
│   │   ├── Models/ (Blog)
│   │   └── Routes/web.php
│   ├── Inquiry/
│   │   ├── Controllers/ (InquiryController)
│   │   ├── Services/ (InquiryService)
│   │   ├── Contracts/ (InquiryRepositoryInterface)
│   │   ├── Repositories/ (InquiryRepository)
│   │   ├── Models/ (Inquiry)
│   │   ├── Requests/ (StoreInquiryRequest)
│   │   └── Routes/web.php
│   ├── Location/
│   │   ├── Controllers/ (ApiController)
│   │   ├── Services/ (LocationService)
│   │   ├── Models/ (City, District, Filter, FilterOption, Amenity)
│   │   ├── Resources/ (CityResource, DistrictResource)
│   │   ├── Enums/ (FilterKey)
│   │   └── Routes/web.php
│   └── Shared/
│       ├── Controllers/ (AuthController, DashboardController, LocaleController, StaticPageController)
│       ├── Services/ (CurrencyService)
│       ├── Models/ (User)
│       └── Routes/web.php
├── Filament/
│   ├── Admin/Resources/           (PropertyResource, AgencyResource, AgentResource, ...)
│   └── Agency/Resources/          (PropertyResource, AgentResource, ...)
└── Providers/
    ├── AppServiceProvider.php
    ├── ModuleServiceProvider.php       (modul routes qeydiyyatı)
    └── Filament/ (AdminPanelProvider, AgencyPanelProvider)

resources/views/                       (bütün Blade view-lar — merkezi)
├── layouts/ (app, navbar, footer, js)
├── pages/
│   ├── property/ (list, details, add, partials/*)
│   ├── blog/ (list, show)
│   ├── agency/ (list)
│   ├── auth/ (login)
│   ├── static/ (about-us, contact, faq)
│   ├── favorites/ (favorites)
│   └── compare/ (compare)
├── agencies/show.blade.php, agents/show.blade.php
├── components/ (breadcrumb, reviews, scroll-top, title, connect-agent,
│   contact-form, property-card, similar-cards, loan-calculator, do-you-need-loan,
│   property/*, modals/*, cards/*)
└── pagination/metraj, filament/*

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

## 5. Modullu Arxitektura Qaydaları

1. **Hər şey modulun içində (view istisna):** PHP kodu yeni funksionallıq əlavə edərkən yalnız müvafiq modulun qovluğuna yazılır (`app/Modules/{Modul}/`). **Blade view-lar isə həmişə `resources/views/` altına** yazılır — modulların içində Views/ qovluğu saxlanılmır.
2. **Thin Controllers:** Controller-lərdə birbaşa Eloquent sorğuları və ağır biznes məntiqi yazılmır. Controller yalnız: request, validasiya, service çağırışı və view.
3. **Biznes məntiqi Service-lərdə:** Hər əməliyyat müvafiq Service metodunda cəmlənir (məs: `PropertyService::create()`, `PropertyService::similar()`).
4. **DB sorğuları Repository-lərdə:** Mürəkkəb sorğular (filtrləmə, axtarış, oxşar elanlar) repository metodlarında saxlanır.
5. **Repository-lər concrete istifadə olunur:** Service-lər constructor-də interface yox, birbaşa concrete repository-ni qəbul edir (məs: `PropertyRepository`). Interface faylları yalnız kontrakt sənədi kimi qalır (`implements` üçün) — əlavə bind-provider tələb olunmur.
6. **Form Request-lər:** Hər POST/PUT üçün ayrıca `FormRequest` sinfi yazılır (`app/Modules/{Modul}/Requests/`). Controller daxilində inline `$request->validate([...])` yazılmır.
7. **API Resources:** JSON cavablar `app/Modules/{Modul}/Resources/` sinifləri ilə formalaşdırılır (məs: `CityResource`); controller-da əl ilə array map yazılmır.
8. **Route faylları:** Hər modulun route-ları `app/Modules/{Modul}/Routes/web.php` faylındadır — əsas `routes/web.php`-yə əlavə edilmir.
9. **View istinadları (namespace prefixi yoxdur):** Bütün view-lar `resources/views/` altındadır və prefixsiz istinad edilir (`view('pages.property.list')`, `@include('components.property-card')`, `<x-connect-agent/>`).
10. **Modullar arası model istinadları:** Eloquent model relation-ları başqa moduldakı modelə istinad edərkən mütləq `use` ilə import edilir; `\App\Modules\...` tam yol ilə də yazıla bilər.
11. **Filament panelləri modellə birbaşa işləyə bilər:** Admin/Agency panelləri Eloquent modelləri ilə birbaşa işləyir — bu normal Filament tərzidir və web-ə aid qaydalara daxil deyil.
12. **User modeli:** `app/Modules/Shared/Models/User.php` — Shared modulunda saxlanılır; digər modullar ona istinad edə bilər.

TEST YAZMA HIC BIR ZAMAN
