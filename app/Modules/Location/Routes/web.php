<?php

use App\Modules\Location\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

// API
Route::get('/api/cities', [ApiController::class, 'cities']);
