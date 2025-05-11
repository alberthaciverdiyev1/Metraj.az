<?php

use Illuminate\Http\Request;

Route::domain(config('app.admin_url'))->group(function () {
    Route::get('/', function (Request $request) {
        return response()->json([
            'message' => 'AdminUI module is working',
        ]);
    });
});



