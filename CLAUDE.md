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

**3. Repositories** — `app/Modules/{Modul}/Repositories/`
- Interface (məs: `PropertyRepositoryInterface`) + Eloquent implementasiya (`EloquentPropertyRepository`) eyni qovluqdadır.
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

**6. Views** — `app/Modules/{Modul}/Views/`
- Blade view-lar modul daxilindədir və `{modul}::` namespace ilə istinad edilir: `view('property::pages.property.list')`, `@include('blog::components.cards.blog')`, `<x-agency::connect-agent/>`.
- View namespace-lərini `ModuleServiceProvider` qeydiyyatdan keçirir.

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
│   │   ├── Repositories/ (PropertyRepositoryInterface, EloquentPropertyRepository)
│   │   ├── Models/ (Property, PropertyImage)
│   │   ├── DTOs/ (CreatePropertyDTO, PropertyFilterDTO)
│   │   ├── Requests/ (StorePropertyRequest)
│   │   ├── Enums/ (PropertyStatus, PropertyType, DealType, SellerType, BuildingType, RepairType)
│   │   ├── Routes/web.php
│   │   └── Views/ (pages/property/*, partials/*, components/*)
│   ├── Agency/
│   │   ├── Controllers/ (AgencyDetailController, AgencyListController, AgentDetailController)
│   │   ├── Services/ (AgencyService, AgentService)
│   │   ├── Repositories/ (AgencyRepositoryInterface, AgentRepositoryInterface, Eloquent*)
│   │   ├── Models/ (Agency, Agent)
│   │   ├── Enums/ (AgencyStatus)
│   │   ├── Routes/web.php
│   │   └── Views/ (agencies/show, agents/show, pages/agency/list, components/*)
│   ├── Blog/
│   │   ├── Controllers/ (BlogController)
│   │   ├── Services/ (BlogService)
│   │   ├── Repositories/ (BlogRepositoryInterface, EloquentBlogRepository)
│   │   ├── Models/ (Blog)
│   │   ├── Routes/web.php
│   │   └── Views/ (pages/blog/*, components/cards/blog)
│   ├── Inquiry/
│   │   ├── Controllers/ (InquiryController)
│   │   ├── Services/ (InquiryService)
│   │   ├── Repositories/ (InquiryRepositoryInterface, EloquentInquiryRepository)
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
│       ├── Routes/web.php
│       └── Views/ (pages/*)
├── Filament/
│   ├── Admin/Resources/           (PropertyResource, AgencyResource, AgentResource, ...)
│   └── Agency/Resources/          (PropertyResource, AgentResource, ...)
├── Models/User.php                (core auth model — modula aid deyil)
└── Providers/
    ├── AppServiceProvider.php
    ├── RepositoryServiceProvider.php   (interface → Eloquent repo bind-ləri)
    ├── ModuleServiceProvider.php       (modul routes + views qeydiyyatı)
    └── Filament/ (AdminPanelProvider, AgencyPanelProvider)
```

Global view-lər (`resources/views/`): `layouts/` (app, navbar, footer, js), `components/` (breadcrumb, reviews,
scroll-top, title), `pagination/metraj`, `filament/*` — bu ümumi (shared) view-lərdir və heç bir modula aid deyil.

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

1. **Hər şey modulun içində:** Yeni funksionallıq əlavə edərkən yalnız müvafiq modulun qovluğuna yazılır (`app/Modules/{Modul}/`). Kök `app/` qovluğuna və ya `routes/web.php`-yə kod əlavə edilmir.
2. **Thin Controllers:** Controller-lərdə birbaşa Eloquent sorğuları və ağır biznes məntiqi yazılmır. Controller yalnız: request, validasiya, service çağırışı və view.
3. **Biznes məntiqi Service-lərdə:** Hər əməliyyat müvafiq Service metodunda cəmlənir (məs: `PropertyService::create()`, `PropertyService::similar()`).
4. **DB sorğuları Repository-lərdə:** Mürəkkəb sorğular (filtrləmə, axtarış, oxşar elanlar) repository metodlarında saxlanır.
5. **Dependency Inversion (Repository-lər üçün):** Service-lər interface-lərdən asılıdır; Eloquent implementasiyalar `RepositoryServiceProvider`-də bind edilir.
6. **Form Request-lər:** Hər POST/PUT üçün ayrıca `FormRequest` sinfi yazılır (`app/Modules/{Modul}/Requests/`). Controller daxilində inline `$request->validate([...])` yazılmır.
7. **API Resources:** JSON cavablar `app/Modules/{Modul}/Resources/` sinifləri ilə formalaşdırılır (məs: `CityResource`); controller-da əl ilə array map yazılmır.
8. **Route faylları:** Hər modulun route-ları `app/Modules/{Modul}/Routes/web.php` faylındadır — əsas `routes/web.php`-yə əlavə edilmir.
9. **View namespace-ləri:** Modul view-larına `{modul}::` prefixi ilə istinad edilir (`view('property::...')`, `@include('blog::components...')`, `<x-agency::connect-agent/>`). Kök `resources/views/components/` yalnız ümumi component-lər üçündür.
10. **Modullar arası model istinadları:** Eloquent model relation-ları başqa moduldakı modelə istinad edərkən mütləq `use` ilə import edilir; `\App\Modules\...` tam yol ilə də yazıla bilər.
11. **Filament panelləri modellə birbaşa işləyə bilər:** Admin/Agency panelləri Eloquent modelləri ilə birbaşa işləyir — bu normal Filament tərzidir və web-ə aid qaydalara daxil deyil.
12. **User modeli:** `app/Models/User.php` core auth modelidir — modullardan asılı deyil, digər modullar ona istinad edə bilər.

TEST YAZMA HIC BIR ZAMAN
