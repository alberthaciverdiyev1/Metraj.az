<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        return view('pages.static.about-us');
    }

    public function contact(): View
    {
        return view('pages.static.contact');
    }

    public function faq(): View
    {
        return view('pages.static.faq');
    }

    public function favorites(): View
    {
        return view('pages.favorites.favorites');
    }

    public function compares(): View
    {
        return view('pages.compare.compare');
    }

    public function login(): View
    {
        return view('pages.auth.login');
    }
}
