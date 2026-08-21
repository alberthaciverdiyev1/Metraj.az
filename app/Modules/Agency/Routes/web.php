<?php

use App\Modules\Agency\Controllers\AgencyDetailController;
use App\Modules\Agency\Controllers\AgencyListController;
use App\Modules\Agency\Controllers\AgentDetailController;
use Illuminate\Support\Facades\Route;

// Agentliklər Siyahısı
Route::get('/agencies', AgencyListController::class)->name('agencies.list');

// Agentlik Profili və Elanları
Route::get('/agentlik/{agency}', AgencyDetailController::class)->name('agencies.show');
Route::get('/agency/{agency}', AgencyDetailController::class)->name('agencies.show.byId');

// Agent / Rieltor Profili və Elanları
Route::get('/agent/{id}', AgentDetailController::class)->name('agents.show');
