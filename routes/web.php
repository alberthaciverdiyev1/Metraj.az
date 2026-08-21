<?php

use App\Http\Controllers\Web\AddPropertyController;
use App\Http\Controllers\Web\AgencyDetailController;
use App\Http\Controllers\Web\AgencyListController;
use App\Http\Controllers\Web\AgentDetailController;
use App\Http\Controllers\Web\ApiController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InquiryController;
use App\Http\Controllers\Web\LocaleController;
use App\Http\Controllers\Web\PropertyDetailController;
use App\Http\Controllers\Web\StaticPageController;
use Illuminate\Support\Facades\Route;

// Ana Səhifə & Axtarış
Route::get('/', HomeController::class)->name('home');
Route::get('/listing', HomeController::class)->name('listing');
Route::get('/properties', HomeController::class);

// Əmlak Detal Səhifəsi
Route::get('/elan/{slug}', PropertyDetailController::class)->name('properties.show');
Route::get('/property/{slug}', PropertyDetailController::class);
Route::get('/properties/{slug}', PropertyDetailController::class);

// Müştəri Müraciəti (Lead göndərişi)
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiries.store');

// Agentlik Profili və Elanları
Route::get('/agentlik/{agency}', AgencyDetailController::class)->name('agencies.show');
Route::get('/agency/{agency}', AgencyDetailController::class)->name('agencies.show.byId');

// Agent / Rieltor Profili və Elanları
Route::get('/agent/{id}', AgentDetailController::class)->name('agents.show');

// API
Route::get('/api/cities', [ApiController::class, 'cities']);
Route::get('/api/subway', [ApiController::class, 'subway']);
Route::get('/api/nearby', [ApiController::class, 'nearby']);

// Statik Səhifələr
Route::get('/about-us', [StaticPageController::class, 'about'])->name('about-us');
Route::get('/contact', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq');

// Favoritlər & Müqayisə
Route::get('/favorites', [StaticPageController::class, 'favorites'])->name('favorites');
Route::get('/compares', [StaticPageController::class, 'compares'])->name('compares');

// Agentliklər Siyahısı
Route::get('/agencies', AgencyListController::class)->name('agencies.list');

// Bloq
Route::get('/blog', [BlogController::class, 'index'])->name('blog.list');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');

// Autentifikasiya & İstifadəçi Paneli
Route::get('/login', [StaticPageController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
Route::get('/my-properties', [DashboardController::class, 'myProperties'])->name('my-properties');

// Elan Əlavə Et
Route::get('/add-property', [AddPropertyController::class, 'create'])->name('add-property');
Route::post('/add-property', [AddPropertyController::class, 'store'])->name('add-property.store');

// Dil & Valyuta Dəyişimi
Route::get('/lang/{locale}', [LocaleController::class, 'switchLanguage'])->name('lang.switch');
Route::get('/currency/{code}', [LocaleController::class, 'switchCurrency'])->name('currency.switch');
