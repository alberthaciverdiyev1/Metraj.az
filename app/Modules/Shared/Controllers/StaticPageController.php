<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Haqqımızda'), 'url' => null],
        ];

        return view('pages.static.about-us', compact('breadcrumbs'));
    }

    public function contact(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Əlaqə'), 'url' => null],
        ];

        return view('pages.static.contact', compact('breadcrumbs'));
    }

    public function faq(): View
    {
        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('FAQ'), 'url' => null],
        ];

        return view('pages.static.faq', compact('breadcrumbs'));
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
