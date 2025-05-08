<?php

use Illuminate\Http\Request;



    Route::get('/', function (Request $request) {
        return response()->json([
            'message' => 'WebUI module is working',
        ]);
    });

