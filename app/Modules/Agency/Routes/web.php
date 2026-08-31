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
Route::match(['GET', 'POST'], '/emlak-ofisi/{agency}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgency'])->name('agencies.reveal-phone');
Route::match(['GET', 'POST'], '/agency/{agency}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgency']);
Route::match(['GET', 'POST'], '/agentlik/{agency}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgency']);

// Emlak Danışmanı / Ajan Profili və İlanları
Route::get('/danisman/{id}', AgentDetailController::class)->name('agents.show');
Route::get('/agent/{id}', AgentDetailController::class);
Route::get('/emlakci/{id}', AgentDetailController::class);
Route::match(['GET', 'POST'], '/danisman/{agent}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgent'])->name('agents.reveal-phone');
Route::match(['GET', 'POST'], '/agent/{agent}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgent']);
Route::match(['GET', 'POST'], '/emlakci/{agent}/reveal-phone', [\App\Modules\Agency\Controllers\AgencyRevealPhoneController::class, 'revealAgent']);
