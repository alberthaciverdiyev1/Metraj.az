<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Modules\WebUI\Controllers\HomeController;
use Modules\WebUI\Controllers\PropertyController;
use Modules\WebUI\Http\Controllers\AddProperty;
use Modules\WebUI\Http\Controllers\Auth\ForgotPasswordController;
use Modules\WebUI\Http\Controllers\Auth\LoginController;
use Modules\WebUI\Http\Controllers\Auth\RegisterController;
use Modules\WebUI\Http\Controllers\Auth\OtpController;
use Modules\WebUI\Http\Controllers\Auth\ResetPasswordController;
use Modules\WebUI\Http\Controllers\Base\BaseController;

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
    Route::get('/blog-detail/{slug}', 'blogDetail')->name('blog.details');
    Route::get('/coming-soon', 'comingSoon')->name('comingSoon');

});

Route::match(['get', 'post'], '/register', [RegisterController::class, 'register'])->name('register');
Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/otp', [OtpController::class, 'otp'])->name('otp');
Route::get('/forgot-password', [ForgotPasswordController::class, 'login'])->name('forgot-password');
Route::get('/reset-password', [ResetPasswordController::class, 'resetpassword'])->name('reset-password');
Route::get('/add-property', [AddProperty::class, 'addproperty'])->name('add-property');

Route::controller(BaseController::class)->group(function () {
    Route::get('/subways', 'subways')->name('subways');
    Route::get('/cities', 'cities')->name('cities');
    Route::get('/features', 'features')->name('features');
    Route::get('/property-types', 'propertyTypes')->name('property-types');
    Route::get('/repair-types', 'repairTypes')->name('repair-types');
    Route::get('/room-count', 'roomCount')->name('room-count');
});

Route::controller(PropertyController::class)->group(function () {
   Route::get('/properties', 'properties')->name('properties');
});



Route::post('/webhook/deploy', function (Request $request) {
    $secret = env('GITHUB_WEBHOOK_SECRET');

    $signature = $request->header('X-Hub-Signature-256');
    $payload = $request->getContent();
    $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    if (!hash_equals($hash, $signature)) {
        Log::warning('Webhook signature mismatch.');
        abort(403, 'Invalid signature');
    }

    exec(base_path('deploy.sh') . ' > /dev/null 2>&1 &');

    return response()->json(['message' => 'Deploy triggered']);
});
