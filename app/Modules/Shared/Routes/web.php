<?php

use App\Modules\Shared\Controllers\AuthController;
use App\Modules\Shared\Controllers\DashboardController;
use App\Modules\Shared\Controllers\LocaleController;
use App\Modules\Shared\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

// Statik Səhifələr
Route::get('/about-us', [StaticPageController::class, 'about'])->name('about-us');
Route::get('/contact', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq');

// Favoritlər & Müqayisə Səhifələri
Route::get('/favorites', [StaticPageController::class, 'favorites'])->name('favorites');
Route::post('/favorites/items', [StaticPageController::class, 'favoritesItems'])->name('favorites.items');
Route::get('/compares', [StaticPageController::class, 'compares'])->name('compares');

// Favoritlər & Müqayisə Backend Əməliyyatları
Route::post('/api/favorites/toggle', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'toggleFavorite'])->name('favorites.toggle');
Route::get('/api/favorites/ids', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'getFavorites'])->name('favorites.ids');
Route::post('/api/favorites/clear', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'clearFavorites'])->name('favorites.clear');

Route::post('/api/compares/toggle', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'toggleCompare'])->name('compares.toggle');
Route::get('/api/compares/ids', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'getCompares'])->name('compares.ids');
Route::post('/api/compares/clear', [\App\Modules\Property\Controllers\FavoriteCompareController::class, 'clearCompares'])->name('compares.clear');

// Autentifikasiya & İstifadəçi Paneli
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
Route::get('/my-properties', [DashboardController::class, 'myProperties'])->name('my-properties');

// Dil & Valyuta Dəyişimi
Route::get('/lang/{locale}', [LocaleController::class, 'switchLanguage'])->name('lang.switch');
Route::get('/currency/{code}', [LocaleController::class, 'switchCurrency'])->name('currency.switch');
