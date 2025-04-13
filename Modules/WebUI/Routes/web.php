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
use Modules\WebUI\Http\Controllers\HomeController;
use Modules\WebUI\Http\Controllers\VehicleController;
use Modules\WebUI\Http\Controllers\WebUIController;

Route::get('/', [HomeController::class, 'index'])->name('web.home');
Route::get('/add-vehicle', [VehicleController::class, 'index'])->name('web.vehicle.add');
