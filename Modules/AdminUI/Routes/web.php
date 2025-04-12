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
use Modules\AdminUI\Http\Controllers\AdminUIController;

Route::prefix('dashboard')
    ->name('admin:adminuis.')
    ->middleware(['auth', 'role_or_permission:developer|admin|show dashboard'])
    ->group(function () {
        Route::resource('/adminuis', AdminUIController::class);
    });
