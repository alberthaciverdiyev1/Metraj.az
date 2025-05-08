<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{

    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $css = [
            'home.css',
        ];
        $js = [
            'home.js',
        ];
        return view('webui::home.index', compact('css', 'js'));
    }

}
