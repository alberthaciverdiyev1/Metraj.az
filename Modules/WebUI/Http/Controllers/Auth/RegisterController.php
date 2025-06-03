<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register()
    {
        $css = ['registerlogin.css', 'app.css'];
        $js = ['gotop.js'];
        $cities = [];

        return view('webui::Auth.register', compact('css', 'js'));
    }
}
