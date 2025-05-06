<?php

namespace Modules\WebUI\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;

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
