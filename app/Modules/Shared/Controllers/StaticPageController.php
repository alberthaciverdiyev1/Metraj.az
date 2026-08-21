<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        return view('shared::pages.static.about-us');
    }

    public function contact(): View
    {
        return view('shared::pages.static.contact');
    }

    public function faq(): View
    {
        return view('shared::pages.static.faq');
    }

    public function favorites(): View
    {
        return view('shared::pages.favorites.favorites');
    }

    public function compares(): View
    {
        return view('shared::pages.compare.compare');
    }

    public function login(): View
    {
        return view('shared::pages.auth.login');
    }
}
