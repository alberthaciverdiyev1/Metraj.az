<?php

use App\Modules\Inquiry\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

// Müştəri Müraciəti (Lead göndərişi)
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiries.store');
