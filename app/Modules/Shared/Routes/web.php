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

// Favoritlər & Müqayisə
Route::get('/favorites', [StaticPageController::class, 'favorites'])->name('favorites');
Route::post('/favorites/items', [StaticPageController::class, 'favoritesItems'])->name('favorites.items');
Route::get('/compares', [StaticPageController::class, 'compares'])->name('compares');

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
