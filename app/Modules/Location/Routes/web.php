<?php

use App\Modules\Location\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

// API
Route::get('/api/cities', [ApiController::class, 'cities']);
Route::get('/api/subway', [ApiController::class, 'subway']);
Route::get('/api/nearby', [ApiController::class, 'nearby']);
