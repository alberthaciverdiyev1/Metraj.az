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

Route::get('currencies',[\Modules\Base\Http\Controllers\EnumController::class, 'currencies']);
Route::get('fuel',[\Modules\Base\Http\Controllers\EnumController::class, 'fuelTypes']);
Route::get('body',[\Modules\Base\Http\Controllers\EnumController::class, 'bodyTypes']);
Route::get('gears',[\Modules\Base\Http\Controllers\EnumController::class, 'gears']);
Route::get('gearbox',[\Modules\Base\Http\Controllers\EnumController::class, 'gearBox']);

Route::middleware('auth:api')->get('/base', function (Request $request) {
    return $request->user();
});
