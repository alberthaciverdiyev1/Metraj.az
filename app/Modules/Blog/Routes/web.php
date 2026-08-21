<?php

use App\Modules\Blog\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Bloq
Route::get('/blog', [BlogController::class, 'index'])->name('blog.list');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
