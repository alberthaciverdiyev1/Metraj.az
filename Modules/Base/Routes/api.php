<?php

use Illuminate\Http\Request;


// Enums

Route::prefix('currency')->group(function () {
    Route::get('/', [\Modules\Base\Http\Controllers\EnumController::class, 'currencies']);
});

Route::prefix('property-types')->group(function () {

    Route::get('/', [\Modules\Base\Http\Controllers\EnumController::class, 'propertyTypes']);
});
Route::prefix('repair-types')->group(function () {

    Route::get('/', [\Modules\Base\Http\Controllers\EnumController::class, 'repairTypes']);
});

Route::prefix('room-count')->group(function () {

    Route::get('/', [\Modules\Base\Http\Controllers\EnumController::class, 'roomCount']);
});
//End Enums


// Start Model Request

Route::prefix('subway')->group(function () {
    Route::get('/', [\Modules\Base\Http\Controllers\SubwayController::class, 'list']);
});


Route::middleware('auth:api')->get('/base', function (Request $request) {
    return $request->user();
});
