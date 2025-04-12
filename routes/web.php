<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    redirect('web.home');
});
