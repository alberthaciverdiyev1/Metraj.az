<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('currency')->group(function () {

    Route::get('/list', [\Modules\Base\Http\Controllers\EnumController::class, 'currencies']);
});

Route::prefix('fuel')->group(function () {

    Route::get('/list', [\Modules\Base\Http\Controllers\EnumController::class, 'fuelTypes']);
});
Route::prefix('body')->group(function () {

    Route::get('/list', [\Modules\Base\Http\Controllers\EnumController::class, 'bodyTypes']);
});

Route::prefix('gear')->group(function () {

    Route::get('/list', [\Modules\Base\Http\Controllers\EnumController::class, 'gears']);
});

Route::prefix('gearbox')->group(function () {

    Route::get('/list', [\Modules\Base\Http\Controllers\EnumController::class, 'gearBox']);
});

Route::middleware('auth:api')->get('/base', function (Request $request) {
    return $request->user();
});
