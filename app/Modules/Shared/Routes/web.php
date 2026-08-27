<?php

use App\Modules\Shared\Controllers\AuthController;
use App\Modules\Shared\Controllers\DashboardController;
use App\Modules\Shared\Controllers\LocaleController;
use App\Modules\Shared\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

// Statik Sayfalar
Route::get('/hakkimizda', [StaticPageController::class, 'about'])->name('about-us');
Route::get('/about-us', [StaticPageController::class, 'about']);
Route::get('/haqqimizda', [StaticPageController::class, 'about']);

Route::get('/iletisim', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/contact', [StaticPageController::class, 'contact']);
Route::get('/elaqe', [StaticPageController::class, 'contact']);

Route::get('/sikca-sorulan-sorular', [StaticPageController::class, 'faq'])->name('faq');
Route::get('/faq', [StaticPageController::class, 'faq']);

// Hukuki Sözleşmeler (Legal Pages)
Route::get('/kullanici-sozlesmesi', [StaticPageController::class, 'userAgreement'])->name('user-agreement');
Route::get('/user-agreement', [StaticPageController::class, 'userAgreement']);
Route::get('/istifadeci-razilasmasi', [StaticPageController::class, 'userAgreement']);

Route::get('/gizlilik-politikasi', [StaticPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy']);
Route::get('/mexfilik-siyaseti', [StaticPageController::class, 'privacyPolicy']);

Route::get('/kullanim-kosullari', [StaticPageController::class, 'termsOfUse'])->name('terms-of-use');
Route::get('/terms-of-use', [StaticPageController::class, 'termsOfUse']);
Route::get('/istifade-qaydalari', [StaticPageController::class, 'termsOfUse']);

// Favoriler & Karşılaştır
Route::get('/favoriler', [StaticPageController::class, 'favorites'])->name('favorites');
Route::get('/favorites', [StaticPageController::class, 'favorites']);
Route::post('/favoriler/items', [StaticPageController::class, 'favoritesItems'])->name('favorites.items');
Route::post('/favorites/items', [StaticPageController::class, 'favoritesItems']);

Route::get('/karsilastir', [StaticPageController::class, 'compares'])->name('compares');
Route::get('/compares', [StaticPageController::class, 'compares']);

// Favoriler & Karşılaştır API
Route::post('/api/favorites/toggle', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'toggleFavorite'])->name('favorites.toggle');
Route::get('/api/favorites/ids', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'getFavorites'])->name('favorites.ids');
Route::post('/api/favorites/clear', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'clearFavorites'])->name('favorites.clear');

Route::post('/api/compares/toggle', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'toggleCompare'])->name('compares.toggle');
Route::get('/api/compares/ids', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'getCompares'])->name('compares.ids');
Route::post('/api/compares/clear', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'clearCompares'])->name('compares.clear');

// Kimlik Doğrulama & Kullanıcı Paneli
Route::get('/giris-yap', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/giris-yap', [AuthController::class, 'login'])->name('login.post');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/kayit-ol', [AuthController::class, 'showRegister'])->name('register');
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/kayit-ol', [AuthController::class, 'register'])->name('register.post');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/cikis-yap', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/kullanici-paneli', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'dashboard']);

Route::get('/profilim', [DashboardController::class, 'profile'])->name('profile');
Route::get('/profile', [DashboardController::class, 'profile']);

Route::get('/ilanlarim', [DashboardController::class, 'myProperties'])->name('my-properties');
Route::get('/my-properties', [DashboardController::class, 'myProperties']);

// Dil & Para Birimi Değişimi
Route::get('/lang/{lang}', [LocaleController::class, 'switchLanguage'])->name('lang.switch');
Route::get('/currency/{code}', [LocaleController::class, 'switchCurrency'])->name('currency.switch');

// Oda Arkadaşı İlanları (Roommates)
Route::get('/oda-arkadasi', [\App\Modules\Roommate\Controllers\RoommateController::class, 'index'])->name('roommates.index');
Route::get('/otaq-yoldasi', [\App\Modules\Roommate\Controllers\RoommateController::class, 'index']);
Route::get('/oda-arkadasi/ilan-ver', [\App\Modules\Roommate\Controllers\RoommateController::class, 'create'])->name('roommates.create');
Route::get('/otaq-yoldasi/elan-ver', [\App\Modules\Roommate\Controllers\RoommateController::class, 'create']);
Route::post('/oda-arkadasi/ilan-ver', [\App\Modules\Roommate\Controllers\RoommateController::class, 'store'])->name('roommates.store');
Route::post('/otaq-yoldasi/elan-ver', [\App\Modules\Roommate\Controllers\RoommateController::class, 'store']);
Route::get('/oda-arkadasi/{slug}', [\App\Modules\Roommate\Controllers\RoommateController::class, 'show'])->name('roommates.show');
Route::get('/otaq-yoldasi/{slug}', [\App\Modules\Roommate\Controllers\RoommateController::class, 'show']);

// Arıyorum (Talep İlanları - Requests)
Route::get('/ariyorum', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'index'])->name('requests.index');
Route::get('/axtariram', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'index']);
Route::get('/ariyorum/ilan-ver', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'create'])->name('requests.create');
Route::get('/axtariram/elan-ver', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'create']);
Route::post('/ariyorum/ilan-ver', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'store'])->name('requests.store');
Route::post('/axtariram/elan-ver', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'store']);
Route::get('/ariyorum/{slug}', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'show'])->name('requests.show');
Route::get('/axtariram/{slug}', [\App\Modules\PropertyRequest\Controllers\PropertyRequestController::class, 'show']);

// Telegram Webhook
Route::post('/api/telegram/webhook', [\App\Http\Controllers\TelegramWebhookController::class, 'handle'])->name('telegram.webhook');

// ===================================================================
// SEO Filtre URL'leri
// ===================================================================
Route::get('/{first}/{second}/{third}', \App\Modules\Property\Controllers\HomeController::class)->name('listing.path3');
Route::get('/{first}/{second}', \App\Modules\Property\Controllers\HomeController::class)->name('listing.path2');
Route::get('/{first}', \App\Modules\Property\Controllers\HomeController::class)->name('listing.path1');
