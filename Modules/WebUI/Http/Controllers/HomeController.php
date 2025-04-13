<?php

namespace Modules\WebUI\Http\Controllers;

use App\Http\Controllers\Controller;
use League\Uri\Http;
use Modules\WebUI\Helpers\API;

class HomeController extends Controller
{
    public function index()
    {
        $brands = API::call('api/brand');

//dd($brands);
        return view('webui::home.index',compact('brands'));
    }
}
