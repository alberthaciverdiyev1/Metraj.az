<?php

use Illuminate\Http\Request;
use Modules\Blog\Http\Controllers\BlogController;

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



Route::controller(BlogController::class)->group(function () {
    Route::get('blog', 'blogs')->name('blog.list');
    Route::get('blog/{slug}', 'blog');
    Route::get('tag', 'tags')->name('tag.list');
});

//Route::middleware('auth:api')->group(function () {
//    Route::resource('/blog', \Modules\Blog\Http\Controllers\BlogController::class);
//});

