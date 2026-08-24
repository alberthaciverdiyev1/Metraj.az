<?php

use App\Modules\Property\Controllers\AddPropertyController;
use App\Modules\Property\Controllers\HomeController;
use App\Modules\Property\Controllers\PropertyDetailController;
use Illuminate\Support\Facades\Route;

// Ana Səhifə & Axtarış
Route::get('/', HomeController::class)->name('home');
Route::get('/listing', HomeController::class)->name('listing');
Route::get('/properties', HomeController::class);

// Əmlak Detal Səhifəsi
Route::get('/elan/{slug}', PropertyDetailController::class)->name('properties.show');
Route::get('/property/{slug}', PropertyDetailController::class);
Route::get('/properties/{slug}', PropertyDetailController::class);

// Elan Əlavə Et
Route::get('/add-property/amenities', [AddPropertyController::class, 'amenities'])->name('add-property.amenities');
Route::get('/add-property', [AddPropertyController::class, 'create'])->name('add-property');
Route::post('/add-property', [AddPropertyController::class, 'store'])->name('add-property.store');
