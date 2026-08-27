<?php

use App\Modules\Agency\Controllers\AgencyDetailController;
use App\Modules\Agency\Controllers\AgencyListController;
use App\Modules\Agency\Controllers\AgentDetailController;
use Illuminate\Support\Facades\Route;

// Emlak Ofisleri Listesi (Turkish: /emlak-ofisleri, alias: /agencies)
Route::get('/emlak-ofisleri', AgencyListController::class)->name('agencies.list');
Route::get('/agencies', AgencyListController::class);

// Emlak Ofisi Profili və İlanları
Route::get('/emlak-ofisi/{agency}', AgencyDetailController::class)->name('agencies.show');
Route::get('/agentlik/{agency}', AgencyDetailController::class);
Route::get('/agency/{agency}', AgencyDetailController::class)->name('agencies.show.byId');

// Emlak Danışmanı / Ajan Profili və İlanları
Route::get('/danisman/{id}', AgentDetailController::class)->name('agents.show');
Route::get('/agent/{id}', AgentDetailController::class);
Route::get('/emlakci/{id}', AgentDetailController::class);
