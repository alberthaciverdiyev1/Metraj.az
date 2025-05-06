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
        return view('webui::index');
    }

}
