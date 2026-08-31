<?php

use App\Modules\Property\Controllers\AddPropertyController;
use App\Modules\Property\Controllers\HomeController;
use App\Modules\Property\Controllers\PropertyDetailController;
use Illuminate\Support\Facades\Route;

// Ana Sayfa & İlan Listesi
Route::get('/', HomeController::class)->name('home');
Route::get('/ilanlar', HomeController::class)->name('listing');
Route::get('/listing', HomeController::class);
Route::get('/properties', HomeController::class);

// İlan Detay Sayfası (Turkish /ilan/{slug}, aliases: /elan, /property, /properties)
Route::get('/ilan/{slug}', PropertyDetailController::class)->name('properties.show');
Route::get('/elan/{slug}', PropertyDetailController::class);
Route::get('/property/{slug}', PropertyDetailController::class);
Route::get('/properties/{slug}', PropertyDetailController::class);

// İlan Ekle (Add Property)
Route::get('/ilan-ver/ozellikler', [AddPropertyController::class, 'amenities'])->name('add-property.amenities');
Route::get('/add-property/amenities', [AddPropertyController::class, 'amenities']);
Route::get('/ilan-ver', [AddPropertyController::class, 'create'])->name('add-property');
Route::get('/add-property', [AddPropertyController::class, 'create']);
Route::post('/ilan-ver', [AddPropertyController::class, 'store'])->name('add-property.store');
Route::post('/add-property', [AddPropertyController::class, 'store']);

// Telefon Numarası Gösterme (Reveal Phone AJAX)
Route::post('/listings/{listing}/reveal-phone', [\App\Modules\Property\Controllers\RevealPhoneController::class, 'reveal'])
    ->middleware('throttle:30,1')
    ->name('listings.reveal-phone');
Route::post('/properties/{listing}/reveal-phone', [\App\Modules\Property\Controllers\RevealPhoneController::class, 'reveal'])
    ->middleware('throttle:30,1');

