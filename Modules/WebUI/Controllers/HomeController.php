<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        dd("aaafjafal");
        $css = [
            'home.css',
        ];
        $js = [
            'home.js',
        ];

        $response = Http::get(config('app.api_url') . '/blog');

        $blogs = $response->successful() ? $response->json() : [];

        dd($blogs);

        return view('webui::home.index', compact('css', 'js'));
    }

}
