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
Route::get('cities', [\Modules\City\Http\Controllers\CityController::class, 'index']);

//Route::middleware('auth:api')->get('/city', function (Request $request) {
//    return $request->user();
//});
