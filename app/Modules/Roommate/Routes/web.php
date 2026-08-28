<?php

use App\Modules\Roommate\Controllers\RoommateController;
use Illuminate\Support\Facades\Route;

// Oda Arkadaşı İlanları (Roommates)
Route::get('/oda-arkadasi', [RoommateController::class, 'index'])->name('roommates.index');
Route::get('/otaq-yoldasi', [RoommateController::class, 'index']);

Route::get('/oda-arkadasi/ilan-ver', [RoommateController::class, 'create'])->name('roommates.create');
Route::get('/otaq-yoldasi/elan-ver', [RoommateController::class, 'create']);

Route::post('/oda-arkadasi/ilan-ver', [RoommateController::class, 'store'])->name('roommates.store');
Route::post('/otaq-yoldasi/elan-ver', [RoommateController::class, 'store']);

Route::get('/oda-arkadasi/{slug}', [RoommateController::class, 'show'])->name('roommates.show');
Route::get('/otaq-yoldasi/{slug}', [RoommateController::class, 'show']);
