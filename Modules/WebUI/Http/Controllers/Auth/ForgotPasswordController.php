<?php

namespace Modules\WebUI\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function login()
    {
        $css = ['registerlogin.css', 'app.css'];
        $js = [''];
        $cities = [];

        return view('webui::Auth.forgot-password', compact('css', 'js'));
    }
}
