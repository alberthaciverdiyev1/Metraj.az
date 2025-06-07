<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {

            $credentials = $request->only('email', 'password');

        } else {
            $css = ['registerlogin.css', 'app.css'];
            $js = ['faqs.js', 'gotop.js'];
            $cities = [];

            return view('webui::Auth.login', compact('css', 'js'));
        }
    }
}
