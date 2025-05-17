<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $css = ['home.css','app.css'];
        $js = ['home.js'];
        $cities = [];

        return view('webui::home.index', compact('css', 'js', 'cities'));
    }

    public function listing()
    {
        $css = ['listing.css','app.css'];
        $js = ['listing.js'];
    

        return view('webui::home.listing', compact('css', 'js'));
    }

     public function agencies()
    {
        $css = ['agencies.css','app.css','listing.css'];
        $js = ['agencies.js','listing.js'];
    

        return view('webui::home.agencies', compact('css', 'js'));
    }
}
