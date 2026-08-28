<?php

use App\Modules\PropertyRequest\Controllers\PropertyRequestController;
use Illuminate\Support\Facades\Route;

// Arıyorum (Talep İlanları - Requests)
Route::get('/ariyorum', [PropertyRequestController::class, 'index'])->name('requests.index');
Route::get('/axtariram', [PropertyRequestController::class, 'index']);

Route::get('/ariyorum/ilan-ver', [PropertyRequestController::class, 'create'])->name('requests.create');
Route::get('/axtariram/elan-ver', [PropertyRequestController::class, 'create']);

Route::post('/ariyorum/ilan-ver', [PropertyRequestController::class, 'store'])->name('requests.store');
Route::post('/axtariram/elan-ver', [PropertyRequestController::class, 'store']);

Route::get('/ariyorum/{slug}', [PropertyRequestController::class, 'show'])->name('requests.show');
Route::get('/axtariram/{slug}', [PropertyRequestController::class, 'show']);
