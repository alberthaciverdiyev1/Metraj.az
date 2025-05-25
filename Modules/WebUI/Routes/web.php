<?php

use Illuminate\Support\Facades\Route;
use Modules\WebUI\Controllers\HomeController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/listing', 'listing')->name('listing');
    Route::get('/agencies', 'agencies')->name('agencies');
    Route::get('/fake-listing', 'fakeListing')->name('fake.listing');
    Route::get('/property/{id}', [HomeController::class, 'propertyDetail'])->name('property.detail');
    Route::get('/agency-detail/{id}', [HomeController::class, 'agencyDetail'])->name('agency.detail');
    Route::get('/agencies/{id}', [HomeController::class, 'agencyDetail'])->name('agency.details');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/check-real-404-path', function () {
        $path = base_path('Modules/WebUI/Resources/views/errors/404.blade.php');

        return response()->json([
            'correct_path' => $path,
            'file_exists' => file_exists($path),
            'file_content' => file_exists($path) ? file_get_contents($path) : null,
            'directory_listing' => scandir(dirname($path))
        ]);
    });
    Route::get('/faqs', 'faqs')->name('faqs');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog-detail/{id}', 'blogDetail')->name('blog.details');
    Route::get('/coming-soon',action:'comingSoon')->name(name:'comingSoon');
});



//
//Route::domain(config('app.url'))->prefix(config('app.url'))->group(function () {
//    Route::get('/', [HomeController::class, 'index'])->name('home');
////    Route::controller(HomeController::class)->group(function () {
////        Route::get('/', 'index')->name('home');
////    });
//});
//
//

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
