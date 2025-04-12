<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Price\Http\Controllers\PriceController;

Route::prefix('dashboard')
    ->name('admin:prices.')
    ->middleware(['auth', 'role_or_permission:developer|admin|show dashboard'])
    ->group(function () {
        Route::resource('/prices', PriceController::class);
    });
