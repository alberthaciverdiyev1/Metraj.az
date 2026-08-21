<?php

use App\Http\Controllers\Web\AgencyDetailController;
use App\Http\Controllers\Web\AgentDetailController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InquiryController;
use App\Http\Controllers\Web\PropertyDetailController;
use Illuminate\Support\Facades\Route;

// Ana Səhifə & Axtarış
Route::get('/', HomeController::class)->name('home');
Route::get('/listing', HomeController::class)->name('listing');

// Əmlak Detal Səhifəsi
Route::get('/elan/{slug}', PropertyDetailController::class)->name('properties.show');

// Müştəri Müraciəti (Lead göndərişi)
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiries.store');

// Agentlik Profili və Elanları
Route::get('/agentlik/{agency}', AgencyDetailController::class)->name('agencies.show');
Route::get('/agency/{agency}', AgencyDetailController::class)->name('agencies.show.byId');

// Agent / Rieltor Profili və Elanları
Route::get('/agent/{id}', AgentDetailController::class)->name('agents.show');

// API Proxy Routes for city filter, metro, and landmarks
Route::get('/properties', HomeController::class);

Route::get('/api/cities', function () {
    $cities = \App\Core\Infrastructure\Persistence\Eloquent\Models\City::with('activeDistricts')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get()
        ->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->name['az'] ?? $city->slug,
                'districts' => $city->activeDistricts->map(function ($dist) {
                    return [
                        'id' => $dist->id,
                        'name' => $dist->name['az'] ?? $dist->slug,
                    ];
                }),
            ];
        });
    return response()->json($cities);
});

Route::get('/api/subway', fn () => response()->json([]));

Route::get('/api/nearby', function () {
    return response()->json([]);
});

// ============================================
// Statik Səhifələr
// ============================================
Route::get('/about-us', fn () => view('pages.static.about-us'))->name('about-us');
Route::get('/contact', fn () => view('pages.static.contact'))->name('contact');
Route::get('/faq', fn () => view('pages.static.faq'))->name('faq');

// ============================================
// Agentliklər Siyahısı
// ============================================
Route::get('/agencies', function () {
    $agencies = \App\Core\Infrastructure\Persistence\Eloquent\Models\Agency::withCount('properties')
        ->where('status', 'active')->get();

    // Heç bir agentliyə bağlı olmayan müstəqil rieltorlar da görsənsin
    $independentAgents = \App\Core\Infrastructure\Persistence\Eloquent\Models\Agent::with('user')
        ->withCount(['properties as published_properties_count' => function ($q) {
            $q->where('status', 'published');
        }])
        ->whereNull('agency_id')
        ->where('is_active', true)
        ->orderByDesc('published_properties_count')
        ->get();

    return view('pages.agency.list', compact('agencies', 'independentAgents'));
})->name('agencies.list');

// ============================================
// Bloq
// ============================================
Route::get('/blog', function () {
    $blogs = \App\Core\Infrastructure\Persistence\Eloquent\Models\Blog::published()
        ->latest('published_at')
        ->paginate(12);

    $breadcrumbs = [
        ['label' => __('Home'), 'url' => '/'],
        ['label' => __('Blog'), 'url' => '/blog'],
    ];

    return view('pages.blog.list', compact('blogs', 'breadcrumbs'));
})->name('blog.list');

Route::get('/blog/{blog:slug}', function (\App\Core\Infrastructure\Persistence\Eloquent\Models\Blog $blog) {
    $related = \App\Core\Infrastructure\Persistence\Eloquent\Models\Blog::published()
        ->where('id', '!=', $blog->id)
        ->where('category', $blog->category)
        ->latest('published_at')
        ->limit(3)
        ->get();

    $breadcrumbs = [
        ['label' => __('Home'), 'url' => '/'],
        ['label' => __('Blog'), 'url' => '/blog'],
        ['label' => $blog->title],
    ];

    return view('pages.blog.show', compact('blog', 'related', 'breadcrumbs'));
})->name('blog.show');

// ============================================
// Favoritlər & Müqayisə
// ============================================
Route::get('/favorites', fn () => view('pages.favorites.favorites'))->name('favorites');
Route::get('/compares', fn () => view('pages.compare.compare'))->name('compares');

// ============================================
// Autentifikasiya & İstifadəçi Paneli
// ============================================
Route::get('/login', fn () => view('pages.auth.login'))->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->email === \App\Models\User::ADMIN_EMAIL) {
            return redirect('/admin');
        }
        return redirect('/agency');
    }
    return redirect('/login');
})->name('dashboard');

Route::get('/profile', function () {
    if (auth()->check()) {
        if (auth()->user()->email === \App\Models\User::ADMIN_EMAIL) {
            return redirect('/admin/profile');
        }
        return redirect('/agency/profile');
    }
    return redirect('/login');
})->name('profile');

Route::get('/my-properties', function () {
    if (auth()->check()) {
        if (auth()->user()->email === \App\Models\User::ADMIN_EMAIL) {
            return redirect('/admin/properties');
        }
        return redirect('/agency/properties');
    }
    return redirect('/login');
})->name('my-properties');

// ============================================
// Elan Əlavə Et
// ============================================
Route::get('/add-property', function () {
    $locationFilter = \App\Core\Infrastructure\Persistence\Eloquent\Models\Filter::where('key', 'location')->first();
    $cities = $locationFilter
        ? \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', $locationFilter->id)->whereNull('parent_id')->get()
        : collect();

    return view('pages.property.add', [
        'cities' => $cities,
        'features' => \App\Core\Infrastructure\Persistence\Eloquent\Models\Amenity::all(),
        'nearbyObjects' => collect(),
        'subways' => collect(),
        'propertyTypes' => \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', 3)->get(),
        'repairTypes' => \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', 5)->get(),
        'currencies' => ['AZN', 'USD', 'EUR', 'GBP', 'TRY'],
        'roomCounts' => [1, 2, 3, 4, 5, 6],
    ]);
})->name('add-property');
// ============================================
// Dil Dəyişimi
// ============================================
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['az', 'en', 'ru'])) {
        session(['lang' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');
