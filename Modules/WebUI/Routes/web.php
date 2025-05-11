<?php

use Modules\WebUI\Controllers\HomeController;


Route::domain(config('app.url'))->prefix(config('app.url'))->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
//    Route::controller(HomeController::class)->group(function () {
//        Route::get('/', 'index')->name('home');
//    });
});
//Route::controller(HomeController::class)->group(function () {
//    Route::get('/', 'index')->name('home');
//    Route::get('/contact-us', 'contact')->name('contact');
//    Route::get('/about/{variable}', 'about')->name('about');
//    Route::get('/service/{slug}', 'serviceDetails')->name('service');
//    Route::get('/news', 'news')->name('news');
//    Route::get('/news/{slug}', 'newsDetails')->name('newsDetails');
//    Route::post('/contact-us', 'contactRequest')->name('contactRequest');
//    Route::get('/search', 'search')->name('search');
//});
