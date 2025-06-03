<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        $css = ['registerlogin.css', 'app.css'];
        $js = ['faqs.js', 'gotop.js'];
        $cities = [];

        return view('webui::Auth.login', compact('css', 'js'));
    }
}
