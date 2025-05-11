<?php

use Illuminate\Http\Request;
use Modules\Blog\Http\Controllers\BlogController;
use Modules\Blog\Http\Controllers\TagController;

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


Route::domain(config('app.api_url'))->group(function () {
    Route::controller(BlogController::class)->group(function () {
        Route::get('blog', 'blogs')->name('blog.list');
        Route::get('blog/{slug}', 'blog')->name('blog.details');
        Route::post('blog', 'store')->name('blog.store');
        Route::put('blog/{slug}', 'update')->name('blog.update');
        Route::delete('blog/{slug}', 'destroy')->name('blog.delete');
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('tag', 'tags')->name('tag.list');
    });
});

//Route::middleware('auth:api')->group(function () {
//    Route::resource('/blog', \Modules\Blog\Http\Controllers\BlogController::class);
//});

